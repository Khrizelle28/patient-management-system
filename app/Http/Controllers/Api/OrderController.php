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

        // Use database transaction
        DB::beginTransaction();

        try {
            // Calculate total
            $totalAmount = 0;
            foreach ($cart->items as $cartItem) {
                $totalAmount += $cartItem->quantity * $cartItem->price;
            }

            // Create order
            $order = Order::create([
                'patient_user_id' => $patientUser->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'status' => 'ready to pickup',
                'pickup_name' => $request->pickup_name,
                'contact_number' => $request->contact_number,
                'notes' => $request->notes,
            ]);

            // Create order items and update product stock
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

                // Decrease product stock
                $product->decrement('stock', $cartItem->quantity);
            }

            // Clear the cart
            $cart->items()->delete();

            DB::commit();

            // Load order with items
            $order->load(['items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
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
            // Restore product stock
            foreach ($order->items as $orderItem) {
                $orderItem->product->increment('stock', $orderItem->quantity);
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
