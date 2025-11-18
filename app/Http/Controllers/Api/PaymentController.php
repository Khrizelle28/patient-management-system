<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaypalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $paypalService;

    public function __construct(PaypalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    // Create payment for appointment or order
    public function createPayment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string|in:appointment,order',
                'item_id' => 'required|integer',
                'amount' => 'required|numeric|min:0.01',
                'currency' => 'nullable|string|in:USD,EUR,GBP,PHP',
                'description' => 'nullable|string|max:255',
            ]);

            $type = $validated['type'];
            $itemId = $validated['item_id'];
            $amount = $validated['amount'];
            $currency = $validated['currency'] ?? 'PHP';
            $description = $validated['description'] ?? ($type === 'appointment' ? 'Appointment Payment' : 'Order Payment');

            // Verify the item exists
            if ($type === 'appointment') {
                $item = Appointment::findOrFail($itemId);
                if ($item->payment_status === 'completed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment already completed for this appointment'
                    ], 400);
                }
            } else {
                $item = Order::findOrFail($itemId);
                if ($item->payment_status === 'completed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment already completed for this order'
                    ], 400);
                }
            }

            // Create PayPal payment
            $payment = $this->paypalService->createPayment(
                $amount,
                $currency,
                $description
            );

            // Update item with PayPal payment ID
            $item->update([
                'paypal_payment_id' => $payment['payment_id'],
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'type' => $type,
                    'item_id' => $itemId,
                    'payment_id' => $payment['payment_id'],
                    'approval_url' => $payment['approval_url'],
                    'status' => $payment['status']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Execute payment (after user approves on PayPal)
    public function executePayment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_id' => 'required|string',
                'payer_id' => 'required|string',
                'type' => 'required|string|in:appointment,order',
            ]);

            // Execute the payment
            $result = $this->paypalService->executePayment(
                $validated['payment_id'],
                $validated['payer_id']
            );

            // Get transaction ID from result
            $transactionId = null;
            if (isset($result['transactions'][0]['related_resources'][0]['sale']['id'])) {
                $transactionId = $result['transactions'][0]['related_resources'][0]['sale']['id'];
            }

            // Update item status based on type
            if ($validated['type'] === 'appointment') {
                $item = Appointment::where('paypal_payment_id', $validated['payment_id'])->first();
            } else {
                $item = Order::where('paypal_payment_id', $validated['payment_id'])->first();
            }

            if ($item) {
                DB::beginTransaction();

                try {
                    $item->update([
                        'payment_status' => 'completed',
                        'paypal_payer_id' => $validated['payer_id'],
                        'paypal_transaction_id' => $transactionId,
                        'payment_completed_at' => now()
                    ]);

                    // Update status based on type
                    if ($validated['type'] === 'appointment') {
                        $item->update(['status' => 'scheduled']);
                    } else {
                        // For orders, update status to ready for pickup and decrement stock
                        $item->update(['status' => 'ready to pickup']);

                        // Decrement stock and increment quantity sold
                        foreach ($item->items as $orderItem) {
                            $orderItem->product->decrement('stock', $orderItem->quantity);
                            $orderItem->product->increment('quantity_sold', $orderItem->quantity);
                        }

                        // Clear the cart
                        $cart = Cart::where('patient_user_id', $item->patient_user_id)->first();
                        if ($cart) {
                            $cart->items()->delete();
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Payment completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment execution error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Check payment status
    public function getPaymentStatus(Request $request, $paymentId): JsonResponse
    {
        try {
            $payment = $this->paypalService->getPaymentDetails($paymentId);

            return response()->json([
                'success' => true,
                'data' => $payment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
