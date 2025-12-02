<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\PatientUser;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // For Administrator and Owner, prepare chart data and statistics
        $doctorIncomeChartData = [];
        $medicineIncomeChartData = [];
        $totalDoctorIncome = 0;
        $totalMedicineIncome = 0;
        $totalPatients = 0;
        $totalDoctors = 0;
        $totalEmployees = 0;
        $lowStockMedicines = collect([]);
        $nearExpiryMedicines = collect([]);
        $latestOrders = collect([]);

        if (auth()->check() && auth()->user()->hasAnyRole(['Administrator', 'Owner', 'Medical Staff'])) {
            // Get low stock medicines (less than 500)
            $lowStockMedicines = Product::select('id', 'name', 'stock', 'price')
                ->where('stock', '<', 500)
                ->orderBy('stock', 'asc')
                ->limit(10)
                ->get();

            // Get near expiry medicines (expiring within 1 month)
            $oneMonthFromNow = Carbon::today()->addMonth();
            $nearExpiryMedicines = Product::select('id', 'name', 'stock', 'expiration_date')
                ->whereBetween('expiration_date', [Carbon::today(), $oneMonthFromNow])
                ->orderBy('expiration_date', 'asc')
                ->limit(10)
                ->get();

            // Get latest medicine orders (excluding completed orders)
            $latestOrders = Order::select('id', 'order_number', 'patient_user_id', 'total_amount', 'status', 'created_at')
                ->with(['patientUser:id,first_name,middle_name,last_name'])
                ->where('status', '!=', 'completed')
                ->where('status', '!=', 'cancelled')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        if (auth()->check() && auth()->user()->hasAnyRole(['Administrator', 'Owner'])) {
            // Get statistics counts
            $totalPatients = PatientUser::count();
            $totalDoctors = User::role('Doctor')->count();
            $totalEmployees = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Administrator', 'Medical Staff', 'Owner']);
            })->count();

            // Get last 12 months of doctor income data
            $doctorIncomeByMonth = Appointment::selectRaw('DATE_FORMAT(appointment_date, "%Y-%m") as month, SUM(total_amount) as total')
                ->whereNotNull('total_amount')
                ->where('status', '!=', 'cancelled')
                ->where('appointment_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->keyBy('month');

            // Get last 12 months of medicine income data
            $medicineIncomeByMonth = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                ->where('payment_status', 'completed')
                ->where('status', '!=', 'cancelled')
                ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->keyBy('month');

            // Prepare chart data for last 12 months
            $months = [];
            $doctorIncomes = [];
            $medicineIncomes = [];

            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthKey = $month->format('Y-m');
                $monthLabel = $month->format('M Y');

                $months[] = $monthLabel;
                $doctorIncomes[] = $doctorIncomeByMonth->get($monthKey)->total ?? 0;
                $medicineIncomes[] = $medicineIncomeByMonth->get($monthKey)->total ?? 0;
            }

            $doctorIncomeChartData = [
                'labels' => $months,
                'data' => $doctorIncomes,
            ];

            $medicineIncomeChartData = [
                'labels' => $months,
                'data' => $medicineIncomes,
            ];

            $totalDoctorIncome = array_sum($doctorIncomes);
            $totalMedicineIncome = array_sum($medicineIncomes);
        }

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

        return view('admin.dashboard', compact(
            'doctorIncomeChartData',
            'medicineIncomeChartData',
            'totalDoctorIncome',
            'totalMedicineIncome',
            'totalPatients',
            'totalDoctors',
            'totalEmployees',
            'lowStockMedicines',
            'nearExpiryMedicines',
            'latestOrders',
            'doctorOwnIncome',
            'totalDoctorOwnIncome'
        ));
    }
}
