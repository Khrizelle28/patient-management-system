<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDoctorSchedule()
    {
        try {
            // Get all doctor schedules with doctor information
            $doctorSchedules = DoctorSchedule::with('doctor')->get();

            // Group by day of week
            $groupedSchedules = $doctorSchedules->groupBy('day_of_week');

            $schedule = [
                'mondayPeople' => $this->formatDoctorData($groupedSchedules['monday'] ?? collect()),
                'tuesdayPeople' => $this->formatDoctorData($groupedSchedules['tuesday'] ?? collect()),
                'wednesdayPeople' => $this->formatDoctorData($groupedSchedules['wednesday'] ?? collect()),
                'thursdayPeople' => $this->formatDoctorData($groupedSchedules['thursday'] ?? collect()),
                'fridayPeople' => $this->formatDoctorData($groupedSchedules['friday'] ?? collect()),
                'saturdayPeople' => $this->formatDoctorData($groupedSchedules['saturday'] ?? collect()),
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
    private function formatDoctorData($doctorSchedules)
    {
        return $doctorSchedules->map(function ($schedule) {
            $doctor = $schedule->doctor;

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
        })->values(); // Reset array keys
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
