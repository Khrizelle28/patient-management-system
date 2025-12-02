<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MedicineIncomeController extends Controller
{
    public function index(Request $request)
    {
        // Get medicine income from orders with date filtering
        $medicineQuery = Order::select(
            'id',
            'order_number',
            'patient_user_id',
            'total_amount',
            'created_at'
        )
            ->with([
                'patientUser:id,first_name,middle_name,last_name',
                'items.product:id,name',
            ])
            ->where('payment_status', 'completed')
            ->where('status', '!=', 'cancelled');

        // Apply date filters for medicine income if provided
        if ($request->filled('medicine_from_date')) {
            $medicineQuery->whereDate('created_at', '>=', $request->medicine_from_date);
        }

        if ($request->filled('medicine_to_date')) {
            $medicineQuery->whereDate('created_at', '<=', $request->medicine_to_date);
        }

        $medicineIncomes = $medicineQuery
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                $medicines = $order->items->map(function ($item) {
                    return $item->product->name ?? 'N/A';
                })->implode(', ');

                $totalQuantity = $order->items->sum('quantity');

                return [
                    'order_number' => $order->order_number,
                    'date' => $order->created_at,
                    'patient' => $order->patientUser->full_name ?? 'N/A',
                    'medicines' => $medicines,
                    'total_quantity' => $totalQuantity,
                    'total_amount' => $order->total_amount,
                ];
            });

        // Calculate total medicine income
        $totalMedicineIncome = $medicineIncomes->sum('total_amount');

        return view('admin.reports.medicine-income', compact('medicineIncomes', 'totalMedicineIncome'));
    }
}
