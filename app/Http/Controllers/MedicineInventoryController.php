<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicineInventoryController extends Controller
{
    public function index(Request $request)
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

            $expirationDate = Carbon::parse($product->expiration_date);
            $today = Carbon::today();
            $oneMonthFromNow = $today->copy()->addMonth();

            $statuses = [];
            $isNearExpiry = $expirationDate->between($today, $oneMonthFromNow);

            // Determine stock status
            if ($product->remaining_stock < 500) {
                $statuses[] = [
                    'label' => 'Low Stock',
                    'class' => 'danger',
                ];
            } elseif ($product->remaining_stock >= 500 && $product->remaining_stock < 1000) {
                $statuses[] = [
                    'label' => 'Good Condition',
                    'class' => 'warning',
                ];
            } else {
                $statuses[] = [
                    'label' => 'Sufficient',
                    'class' => 'success',
                ];
            }

            // Add Near Expiry status if applicable
            if ($isNearExpiry) {
                $statuses[] = [
                    'label' => 'Near Expiry',
                    'class' => 'danger',
                ];
            }

            $product->statuses = $statuses;

            return $product;
        });

        return view('admin.reports.medicine-inventory', compact('products'));
    }
}
