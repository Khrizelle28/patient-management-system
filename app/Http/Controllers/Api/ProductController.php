<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Get all products.
     */
    public function index(): JsonResponse
    {
        $products = Product::query()->latest()->get();

        return response()->json([
            'data' => ProductResource::collection($products),
        ]);
    }

    /**
     * Get available products (in stock only).
     */
    public function getAvailableProducts(): JsonResponse
    {
        $products = Product::query()
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'count' => $products->count(),
        ]);
    }
}
