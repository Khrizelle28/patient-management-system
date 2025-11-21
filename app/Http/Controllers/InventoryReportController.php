<?php

namespace App\Http\Controllers;

use App\Models\Product;

class InventoryReportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $products = Product::select(
            'id',
            'name',
            'stock',
            'quantity_sold',
            'price',
            'expiration_date'
        )->get()->map(function ($product) {
            $product->remaining_stock = $product->stock;
            $product->overall_stock = $product->stock + $product->quantity_sold;
            $product->total_income = $product->quantity_sold * $product->price;

            if ($product->remaining_stock < 500) {
                $product->status = 'Low Stock';
                $product->status_class = 'danger';
            } elseif ($product->remaining_stock >= 500 && $product->remaining_stock < 1000) {
                $product->status = 'Good Condition';
                $product->status_class = 'warning';
            } else {
                $product->status = 'Sufficient';
                $product->status_class = 'success';
            }

            return $product;
        });

        // Calculate total income from all products
        $totalIncome = $products->sum('total_income');

        return view('report.inventory', compact('products', 'totalIncome'));
    }
}
