<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get the authenticated patient user's cart.
     */
    public function index(): JsonResponse
    {
        $patientUser = auth('sanctum')->user();

        $cart = Cart::with(['items.product'])->firstOrCreate(
            ['patient_user_id' => $patientUser->id]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cart,
                'items' => $cart->items,
                'total' => $cart->total,
                'items_count' => $cart->items->sum('quantity'),
            ],
        ]);
    }

    /**
     * Add item to cart.
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        $patientUser = auth('sanctum')->user();

        // Check if user is authenticated
        if (! $patientUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in.',
            ], 401);
        }

        $validated = $request->validated();
        $product = Product::query()->findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.',
            ], 400);
        }

        $cart = Cart::query()->firstOrCreate(['patient_user_id' => $patientUser->id]);

        $cartItem = CartItem::query()->where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];

            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items. Stock limit reached.',
                ], 400);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::query()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully.',
            'cart_item' => $cartItem,
        ], 200);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $patientUser = auth('sanctum')->user();
        $cart = Cart::query()->where('patient_user_id', $patientUser->id)->firstOrFail();

        $cartItem = CartItem::query()->where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $product = $cartItem->product;

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.',
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cart->load(['items.product']);

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully.',
            'data' => [
                'cart' => $cart,
                'item' => $cartItem->load('product'),
                'total' => $cart->total,
            ],
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function destroy(string $id): JsonResponse
    {
        $patientUser = auth('sanctum')->user();
        $cart = Cart::query()->where('patient_user_id', $patientUser->id)->firstOrFail();

        $cartItem = CartItem::query()->where('cart_id', $cart->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        $cart->load(['items.product']);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.',
            'data' => [
                'cart' => $cart,
                'total' => $cart->total,
            ],
        ]);
    }

    /**
     * Clear all items from cart.
     */
    public function clear(): JsonResponse
    {
        $patientUser = auth('sanctum')->user();
        $cart = Cart::query()->where('patient_user_id', $patientUser->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);
    }
}
