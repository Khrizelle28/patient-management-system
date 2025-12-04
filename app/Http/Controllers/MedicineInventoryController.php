<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicineInventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('batches')
            ->select(
                'id',
                'name',
                'stock',
                'quantity_sold',
                'price',
                'expiration_date'
            )->get()->map(function ($product) {
                $batches = $product->batches;

                // If product has batches, group them
                if ($batches->isNotEmpty()) {
                    // Calculate total remaining stock across all batches
                    $totalRemainingStock = $batches->sum('remaining_quantity');

                    $batchesData = $batches->map(function ($batch) use ($product) {
                        $batchData = (object) [
                            'overall_stock' => $batch->quantity,
                            'quantity_sold' => $batch->quantity_sold,
                            'remaining_stock' => $batch->remaining_quantity,
                            'expiration_date' => $batch->expiration_date,
                            'total_income' => $batch->quantity_sold * $product->price,
                        ];

                        $expirationDate = Carbon::parse($batch->expiration_date);
                        $today = Carbon::today();
                        $oneMonthFromNow = $today->copy()->addMonth();

                        $statuses = [];
                        $isNearExpiry = $expirationDate->between($today, $oneMonthFromNow);

                        // Add Near Expiry status if applicable (per batch)
                        if ($isNearExpiry) {
                            $statuses[] = [
                                'label' => 'Near Expiry ('.number_format($batchData->remaining_stock).' units expiring)',
                                'class' => 'danger',
                            ];
                        }

                        $batchData->statuses = $statuses;

                        return $batchData;
                    });

                    // Determine overall stock status based on total remaining stock
                    $consolidatedStatuses = [];

                    if ($totalRemainingStock < 500) {
                        $consolidatedStatuses[] = [
                            'label' => 'Low Stock',
                            'class' => 'danger',
                        ];
                    } elseif ($totalRemainingStock >= 500 && $totalRemainingStock < 1000) {
                        $consolidatedStatuses[] = [
                            'label' => 'Good Condition',
                            'class' => 'warning',
                        ];
                    } else {
                        $consolidatedStatuses[] = [
                            'label' => 'Sufficient',
                            'class' => 'success',
                        ];
                    }

                    // Calculate total near expiry units across all batches
                    $totalNearExpiryUnits = 0;
                    foreach ($batchesData as $batch) {
                        foreach ($batch->statuses as $status) {
                            if (str_contains($status['label'], 'Near Expiry')) {
                                $totalNearExpiryUnits += $batch->remaining_stock;
                            }
                        }
                    }

                    // Add consolidated Near Expiry status if there are expiring units
                    if ($totalNearExpiryUnits > 0) {
                        $consolidatedStatuses[] = [
                            'label' => 'Near Expiry ('.number_format($totalNearExpiryUnits).' units expiring)',
                            'class' => 'danger',
                        ];
                    }

                    return (object) [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'batches' => $batchesData,
                        'has_multiple_batches' => $batchesData->count() > 1,
                        'consolidated_statuses' => $consolidatedStatuses,
                    ];
                }

                // Fallback for products without batches (legacy data)
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
                        'label' => 'Near Expiry ('.number_format($product->remaining_stock).' units expiring)',
                        'class' => 'danger',
                    ];
                }

                $product->statuses = $statuses;
                $product->batches = null;
                $product->has_multiple_batches = false;

                return $product;
            });

        return view('admin.reports.medicine-inventory', compact('products'));
    }
}
