<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        // Build query for doctor incomes with date filtering
        $query = Appointment::select(
            'id',
            'doctor_id',
            'patient_id',
            'appointment_date',
            'total_amount'
        )
            ->with([
                'doctor:id,first_name,middle_name,last_name,suffix',
                'patient:id,first_name,middle_name,last_name',
            ])
            ->whereNotNull('total_amount')
            ->where('status', '!=', 'cancelled');

        // Apply date filters if provided
        if ($request->filled('from_date')) {
            $query->whereDate('appointment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('appointment_date', '<=', $request->to_date);
        }

        $doctorIncomes = $query
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'date' => $appointment->appointment_date,
                    'doctor' => $appointment->doctor->full_name ?? 'N/A',
                    'patient' => $appointment->patient->full_name ?? 'N/A',
                    'professional_fee' => $appointment->total_amount,
                ];
            });

        // Calculate total professional fee
        $totalProfessionalFee = $doctorIncomes->sum('professional_fee');

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

        // Get doctor's own income (for doctors only)
        $doctorOwnIncome = collect([]);
        $totalDoctorOwnIncome = 0;

        if (auth()->check() && auth()->user()->hasRole('Doctor')) {
            $doctorOwnIncome = Appointment::select(
                'id',
                'patient_id',
                'appointment_date',
                'total_amount'
            )
                ->with([
                    'patient:id,first_name,middle_name,last_name',
                ])
                ->where('doctor_id', auth()->id())
                ->whereNotNull('total_amount')
                ->where('status', '!=', 'cancelled')
                ->orderBy('appointment_date', 'desc')
                ->get()
                ->map(function ($appointment) {
                    return [
                        'date' => $appointment->appointment_date,
                        'patient' => $appointment->patient->full_name ?? 'N/A',
                        'professional_fee' => $appointment->total_amount,
                    ];
                });

            $totalDoctorOwnIncome = $doctorOwnIncome->sum('professional_fee');
        }

        return view('admin.dashboard', compact('products', 'doctorIncomes', 'totalProfessionalFee', 'medicineIncomes', 'totalMedicineIncome', 'doctorOwnIncome', 'totalDoctorOwnIncome'));
    }
}
