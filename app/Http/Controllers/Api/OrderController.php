<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Get user's order history.
     */
    public function index(): JsonResponse
    {
        $patientUser = auth('sanctum')->user();

        $orders = Order::query()
            ->with(['items.product'])
            ->where('patient_user_id', $patientUser->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Place order from cart.
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'pickup_name' => 'required|string',
            'contact_number' => 'required|string',
            'email' => 'nullable|email',
            'notes' => 'nullable|string',
        ]);

        $patientUser = auth('sanctum')->user();

        if (! $patientUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in.',
            ], 401);
        }

        // Get user's cart with items
        $cart = Cart::query()
            ->with(['items.product'])
            ->where('patient_user_id', $patientUser->id)
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Validate stock availability for all items
        foreach ($cart->items as $cartItem) {
            $product = $cartItem->product;

            if ($product->stock < $cartItem->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}. Available: {$product->stock}",
                ], 400);
            }
        }

        // Auto-cancel old pending orders for this patient
        // This prevents accumulation of failed/abandoned orders
        Order::where('patient_user_id', $patientUser->id)
            ->where('status', 'pending_payment')
            ->where('payment_status', 'pending')
            ->update([
                'status' => 'cancelled',
                'notes' => DB::raw("CONCAT(COALESCE(notes, ''), ' [Auto-cancelled: Payment not completed]')"),
            ]);

        Log::info("Auto-cancelled old pending orders for patient: {$patientUser->id}");

        // Use database transaction
        DB::beginTransaction();

        try {
            // Calculate total
            $totalAmount = 0;
            foreach ($cart->items as $cartItem) {
                $totalAmount += $cartItem->quantity * $cartItem->price;
            }

            // Create order with pending payment status
            $order = Order::create([
                'patient_user_id' => $patientUser->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'pickup_name' => $request->pickup_name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'notes' => $request->notes,
                'payment_status' => 'pending',
            ]);

            // Create order items but don't update product stock yet
            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->quantity * $cartItem->price,
                ]);

                // Note: Stock will be decremented when payment is completed
            }

            // Keep cart items for now - will clear after payment
            // $cart->items()->delete();

            DB::commit();

            // Load order with items
            $order->load(['items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Order created. Please complete payment to confirm.',
                'data' => [
                    'order' => $order,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single order details.
     */
    public function show(string $id): JsonResponse
    {
        $patientUser = auth('sanctum')->user();

        $order = Order::query()
            ->with(['items.product'])
            ->where('patient_user_id', $patientUser->id)
            ->where('id', $id)
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Cancel order (only if pending).
     */
    public function cancel(string $id): JsonResponse
    {
        $patientUser = auth('sanctum')->user();

        $order = Order::query()
            ->where('patient_user_id', $patientUser->id)
            ->where('id', $id)
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if ($order->status !== 'ready to pickup') {
            return response()->json([
                'success' => false,
                'message' => 'Only orders ready to pickup can be cancelled.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Restore product stock and decrement quantity sold
            foreach ($order->items as $orderItem) {
                $orderItem->product->increment('stock', $orderItem->quantity);
                $orderItem->product->decrement('quantity_sold', $orderItem->quantity);
            }

            // Delete order items
            $order->items()->delete();

            // Delete the order
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order. Please try again.',
            ], 500);
        }
    }
}
