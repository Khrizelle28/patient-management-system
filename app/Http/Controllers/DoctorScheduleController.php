<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorMaxPatient;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     * Optionally accepts a date parameter to check availability for a specific date.
     */
    public function getDoctorSchedule(Request $request)
    {
        try {
            // Get the date parameter or default to today
            $selectedDate = $request->input('date') ? \Carbon\Carbon::parse($request->input('date')) : today();

            // Get all doctor schedules with doctor information
            $doctorSchedules = DoctorSchedule::with('doctor')->get();

            // Group by day of week
            $groupedSchedules = $doctorSchedules->groupBy('day_of_week');

            $schedule = [
                'mondayPeople' => $this->formatDoctorData($groupedSchedules['monday'] ?? collect(), $selectedDate),
                'tuesdayPeople' => $this->formatDoctorData($groupedSchedules['tuesday'] ?? collect(), $selectedDate),
                'wednesdayPeople' => $this->formatDoctorData($groupedSchedules['wednesday'] ?? collect(), $selectedDate),
                'thursdayPeople' => $this->formatDoctorData($groupedSchedules['thursday'] ?? collect(), $selectedDate),
                'fridayPeople' => $this->formatDoctorData($groupedSchedules['friday'] ?? collect(), $selectedDate),
                'saturdayPeople' => $this->formatDoctorData($groupedSchedules['saturday'] ?? collect(), $selectedDate),
            ];
            // dd($schedule);
            return response()->json($schedule);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Failed to fetch doctor schedule',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format doctor data for consistent API response
     */
    private function formatDoctorData($doctorSchedules, $selectedDate)
    {
        return $doctorSchedules->map(function ($schedule) use ($selectedDate) {
            $doctor = $schedule->doctor;

            // Get the day of week for both the schedule and selected date
            $scheduleDayOfWeek = strtolower($schedule->day_of_week); // 'monday', 'tuesday', etc.
            $selectedDayOfWeek = strtolower($selectedDate->format('l')); // 'monday', 'tuesday', etc.

            // Only check capacity if this schedule's day matches the selected date's day
            if ($scheduleDayOfWeek === $selectedDayOfWeek) {
                // Check if doctor has reached max patients for the selected date
                if ($this->isDoctorAtMaxCapacity($doctor->id ?? null, $selectedDate)) {
                    return null; // Remove doctor from this day's list - they're at max capacity
                }
            }

            // Doctor is available on this day
            return [
                'id' => (string) $doctor->id ?? null,
                'name' => $doctor ? $this->formatDoctorName($doctor) : 'Unknown Doctor',
                'specialty' => $this->getDoctorSpecialty($doctor),
                'schedule' => $this->formatTimeRange($schedule->start_time, $schedule->end_time),
                'start_time' => $schedule->start_time ? $schedule->start_time : null,
                'end_time' => $schedule->end_time ? $schedule->end_time : null,
                'is_available' => true,
                'day_of_week' => $schedule->day_of_week,
                'license_no' => $doctor->license_no ?? null,
                'ptr_no' => $doctor->ptr_no ?? null,
                'email' => $doctor->email ?? null,
                'created_at' => $schedule->created_at,
                'updated_at' => $schedule->updated_at,
            ];
        })->filter()->values(); // Filter out null values and reset array keys
    }

    /**
     * Check if doctor has reached maximum patients for the selected date
     */
    private function isDoctorAtMaxCapacity($doctorId, $selectedDate)
    {
        if (!$doctorId) {
            return false;
        }

        // Get doctor's max patients setting
        $maxPatientRecord = DoctorMaxPatient::where('doctor_id', $doctorId)->first();

        if (!$maxPatientRecord) {
            return false; // No limit set, doctor is available
        }

        $maxPatients = (int) $maxPatientRecord->max_patients;

        // Count appointments for this doctor on the selected date (excluding cancelled)
        $dateAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $selectedDate)
            ->whereIn('status', ['scheduled', 'completed'])
            ->count();

        // Return true if doctor has reached or exceeded max capacity
        return $dateAppointments >= $maxPatients;
    }

    /**
     * Format doctor name from user data
     */
    private function formatDoctorName($doctor)
    {
        if (!$doctor) {
            return 'Unknown Doctor';
        }

        $fullName = $doctor->full_name ?? trim($doctor->first_name . ' ' . $doctor->last_name);
        return 'Dr. ' . $fullName;
    }

    /**
     * Get doctor specialty (you might need to add a specialty field to users table)
     */
    private function getDoctorSpecialty($doctor)
    {
        // If you have a specialty field in users table, use it:
        // return $doctor->specialty ?? 'General Practice';

        // For now, return a default or determine based on license/other data
        return 'Obstetrician - Gynecologyst';
    }

    /**
     * Format time range for display
     */
    private function formatTimeRange($startTime, $endTime)
    {
        try {
            if (!$startTime || !$endTime) {
                return "Schedule not set";
            }

            $start = \Carbon\Carbon::parse($startTime)->format('g:i A');
            $end = \Carbon\Carbon::parse($endTime)->format('g:i A');
            return "$start - $end";
        } catch (\Exception $e) {
            return "Schedule not set";
        }
    }

}
