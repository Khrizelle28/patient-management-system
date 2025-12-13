<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Appointment;
use App\Models\Cart;
use App\Models\Order;
use App\Services\PaypalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                        'message' => 'Payment already completed for this appointment',
                    ], 400);
                }

                // If payment is pending from a previous attempt, clear the old payment ID
                // to allow retry with a new PayPal payment
                if ($item->payment_status === 'pending' && $item->paypal_payment_id) {
                    Log::info("Clearing old payment ID for appointment retry: {$itemId}");
                }
            } else {
                $item = Order::findOrFail($itemId);
                if ($item->payment_status === 'completed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment already completed for this order',
                    ], 400);
                }

                // If payment is pending from a previous attempt, allow retry
                if ($item->payment_status === 'pending' && $item->paypal_payment_id) {
                    Log::info("Clearing old payment ID for order retry: {$itemId}");
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
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'type' => $type,
                    'item_id' => $itemId,
                    'payment_id' => $payment['payment_id'],
                    'approval_url' => $payment['approval_url'],
                    'status' => $payment['status'],
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
                        'payment_completed_at' => now(),
                    ]);

                    // Update status based on type
                    if ($validated['type'] === 'appointment') {
                        $item->update(['status' => 'scheduled']);

                        // Load relationships for email
                        $item->load(['patient', 'doctor']);

                        // Send appointment invoice email
                        $this->sendAppointmentInvoice($item, $transactionId);
                    } else {
                        // For orders, update status to ready for pickup and decrement stock
                        $item->update(['status' => 'ready to pickup']);

                        // Decrement stock and increment quantity sold using FIFO for batches
                        foreach ($item->items as $orderItem) {
                            $product = $orderItem->product;
                            $remainingQuantity = $orderItem->quantity;

                            // Check if product has batches
                            $batches = $product->batches()
                                ->whereRaw('quantity - quantity_sold > 0')
                                ->orderBy('expiration_date', 'asc')
                                ->get();

                            if ($batches->isNotEmpty()) {
                                // Deduct from batches using FIFO (oldest expiration first)
                                foreach ($batches as $batch) {
                                    if ($remainingQuantity <= 0) {
                                        break;
                                    }

                                    $availableInBatch = $batch->quantity - $batch->quantity_sold;
                                    $deductAmount = min($remainingQuantity, $availableInBatch);

                                    $batch->increment('quantity_sold', $deductAmount);
                                    $remainingQuantity -= $deductAmount;
                                }
                            }

                            // Update product totals
                            $product->decrement('stock', $orderItem->quantity);
                            $product->increment('quantity_sold', $orderItem->quantity);
                        }

                        // Clear the cart
                        $cart = Cart::where('patient_user_id', $item->patient_user_id)->first();
                        if ($cart) {
                            $cart->items()->delete();
                        }

                        // Load relationships for email
                        $item->load(['patientUser', 'items.product']);

                        // Send order invoice email
                        $this->sendOrderInvoice($item, $transactionId);
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
                'message' => 'Payment completed successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Payment execution error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
                'data' => $payment,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Send appointment invoice email.
     */
    private function sendAppointmentInvoice(Appointment $appointment, ?string $transactionId): void
    {
        try {
            $services = [];

            // Add service type
            if ($appointment->service_price > 0) {
                $services[] = [
                    'name' => ucfirst($appointment->service_type),
                    'price' => '₱'.number_format($appointment->service_price, 2),
                    'total' => '₱'.number_format($appointment->service_price, 2),
                ];
            }

            // Add pap smear if applicable
            if ($appointment->has_pap_smear && $appointment->pap_smear_price > 0) {
                $services[] = [
                    'name' => 'Pap Smear',
                    'price' => '₱'.number_format($appointment->pap_smear_price, 2),
                    'total' => '₱'.number_format($appointment->pap_smear_price, 2),
                ];
            }

            // Add medical certificate if applicable
            if ($appointment->needs_medical_certificate && $appointment->medical_certificate_price > 0) {
                $services[] = [
                    'name' => 'Medical Certificate',
                    'price' => '₱'.number_format($appointment->medical_certificate_price, 2),
                    'total' => '₱'.number_format($appointment->medical_certificate_price, 2),
                ];
            }

            $invoiceData = [
                'invoice_number' => str_pad($appointment->id, 5, '0', STR_PAD_LEFT),
                'date' => now()->format('m/d/Y'),
                'patient_name' => $appointment->patient->full_name ?? 'N/A',
                'patient_contact' => $appointment->patient->contact_number ?? $appointment->patient->email ?? 'N/A',
                'total_amount' => '₱'.number_format($appointment->total_amount, 2),
                'services' => $services,
                'payment_method' => 'Paypal',
                'reference_number' => $transactionId ?? $appointment->paypal_transaction_id ?? 'N/A',
            ];

            // Use email from appointment if provided, otherwise use patient's email
            $emailTo = $appointment->email ?? ($appointment->patient ? $appointment->patient->email : null);

            if ($emailTo) {
                Mail::to($emailTo)->send(new InvoiceMail($invoiceData, 'appointment'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send appointment invoice email: '.$e->getMessage());
        }
    }

    /**
     * Send order invoice email.
     */
    private function sendOrderInvoice(Order $order, ?string $transactionId): void
    {
        try {
            $items = [];

            foreach ($order->items as $orderItem) {
                $items[] = [
                    'name' => $orderItem->product->name ?? 'Product',
                    'quantity' => $orderItem->quantity,
                    'price' => '₱'.number_format($orderItem->price, 2),
                    'total' => '₱'.number_format($orderItem->subtotal, 2),
                ];
            }

            $invoiceData = [
                'invoice_number' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'date' => now()->format('m/d/Y'),
                'patient_name' => $order->pickup_name ?? $order->patientUser->full_name ?? 'N/A',
                'patient_contact' => $order->contact_number ?? $order->patientUser->contact_number ?? $order->patientUser->email ?? 'N/A',
                'total_amount' => '₱'.number_format($order->total_amount, 2),
                'items' => $items,
                'payment_method' => 'Paypal',
                'reference_number' => $transactionId ?? $order->paypal_transaction_id ?? 'N/A',
            ];

            // Use email from order if provided, otherwise use patient's email
            $emailTo = $order->email ?? ($order->patientUser ? $order->patientUser->email : null);

            if ($emailTo) {
                Mail::to($emailTo)->send(new InvoiceMail($invoiceData, 'order'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order invoice email: '.$e->getMessage());
        }
    }
}
