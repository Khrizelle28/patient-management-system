<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;

class InventoryReportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $products = Product::select(
            'id',
            'name',
            'stock',
            'expiration_date'
        )
            ->where('stock', '<', 500)
            ->get()
            ->map(function ($product) {
                $expirationDate = Carbon::parse($product->expiration_date);
                $today = Carbon::today();
                $oneMonthFromNow = $today->copy()->addMonth();

                $statuses = [];
                $isNearExpiry = $expirationDate->between($today, $oneMonthFromNow);

                // Determine stock status
                if ($product->stock < 500) {
                    $statuses[] = [
                        'label' => 'Low Stock',
                        'class' => 'danger',
                    ];
                } else {
                    $statuses[] = [
                        'label' => 'Good Condition',
                        'class' => 'success',
                    ];
                }

                // Add Near Expiry status if applicable
                if ($isNearExpiry) {
                    $statuses[] = [
                        'label' => 'Near Expiry',
                        'class' => 'warning',
                    ];
                }

                $product->statuses = $statuses;

                return $product;
            });

        return view('report.inventory', compact('products'));
    }
}
