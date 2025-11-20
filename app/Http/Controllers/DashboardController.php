<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
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

        $doctorIncomes = Appointment::select(
            'doctor_id',
            'appointment_date',
            DB::raw('SUM(total_amount) as professional_fee')
        )
            ->with('doctor:id,first_name,middle_name,last_name,suffix')
            ->whereNotNull('total_amount')
            ->where('status', '!=', 'cancelled')
            ->groupBy('doctor_id', 'appointment_date')
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'date' => $appointment->appointment_date,
                    'doctor' => $appointment->doctor->full_name ?? 'N/A',
                    'professional_fee' => $appointment->professional_fee,
                ];
            });

        return view('admin.dashboard', compact('products', 'doctorIncomes'));
    }
}
