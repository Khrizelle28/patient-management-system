<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorIncomeController extends Controller
{
    public function index(Request $request)
    {
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

        // If logged-in user is a doctor, filter to show only their income
        if (auth()->user()->hasRole('Doctor')) {
            $query->where('doctor_id', auth()->id());
        }

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

        return view('admin.reports.doctor-income', compact('doctorIncomes', 'totalProfessionalFee'));
    }
}
