<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicationAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicationAlertController extends Controller
{
    /**
     * Get all medication alerts for a specific patient.
     */
    public function index($patientId)
    {
        try {
            $alerts = MedicationAlert::where('patient_id', $patientId)
                ->orderBy('time', 'asc')
                ->orderBy('period', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Medication alerts retrieved successfully',
                'data' => $alerts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve medication alerts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created medication alert.
     */
    public function store(Request $request)
    {
        \Log::info('Store medication alert called', [
            'all_data' => $request->all(),
            'prescribed_pieces' => $request->prescribed_pieces,
            'times_per_day' => $request->times_per_day,
            'start_day' => $request->start_day,
        ]);

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patient_users,id',
            'time' => 'required|string',
            'period' => 'required|in:AM,PM',
            'medication_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'is_enabled' => 'boolean',
            'selected_days' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            // Prescribed pieces fields
            'prescribed_pieces' => 'nullable|integer|min:1',
            'times_per_day' => 'nullable|integer|min:1|max:4',
            'start_day' => 'nullable|string',
            'first_dose_time' => 'nullable|string',
            'first_dose_period' => 'nullable|in:AM,PM',
        ]);

        if ($validator->fails()) {
            \Log::error('Medication alert validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            \Log::info('Creating medication alert', ['data' => $request->all()]);
            $alert = MedicationAlert::create([
                'patient_id' => $request->patient_id,
                'time' => $request->time,
                'period' => $request->period,
                'medication_name' => $request->medication_name,
                'remarks' => $request->remarks,
                'is_enabled' => $request->is_enabled ?? true,
                'selected_days' => $request->selected_days ?? '', // Default to empty string if null
                'duration_days' => $request->duration_days ?? 0,
                'start_date' => $request->start_date ?? now()->format('Y-m-d'),
                // Prescribed pieces fields
                'prescribed_pieces' => $request->prescribed_pieces,
                'times_per_day' => $request->times_per_day,
                'start_day' => $request->start_day,
                'first_dose_time' => $request->first_dose_time,
                'first_dose_period' => $request->first_dose_period,
            ]);

            \Log::info('Medication alert created successfully', [
                'id' => $alert->id,
                'prescribed_pieces' => $alert->prescribed_pieces,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medication alert created successfully',
                'alert' => $alert,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create medication alert', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create medication alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified medication alert.
     */
    public function show($id)
    {
        try {
            $alert = MedicationAlert::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Medication alert retrieved successfully',
                'alert' => $alert,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Medication alert not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified medication alert.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patient_users,id',
            'time' => 'required|string',
            'period' => 'required|in:AM,PM',
            'medication_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'is_enabled' => 'boolean',
            'selected_days' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            // Prescribed pieces fields
            'prescribed_pieces' => 'nullable|integer|min:1',
            'times_per_day' => 'nullable|integer|min:1|max:4',
            'start_day' => 'nullable|string',
            'first_dose_time' => 'nullable|string',
            'first_dose_period' => 'nullable|in:AM,PM',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $alert = MedicationAlert::findOrFail($id);

            $alert->update([
                'patient_id' => $request->patient_id,
                'time' => $request->time,
                'period' => $request->period,
                'medication_name' => $request->medication_name,
                'remarks' => $request->remarks,
                'is_enabled' => $request->is_enabled ?? $alert->is_enabled,
                'selected_days' => $request->selected_days ?? ($alert->selected_days ?? ''),
                'duration_days' => $request->duration_days ?? ($alert->duration_days ?? 0),
                'start_date' => $request->start_date ?? $alert->start_date,
                // Prescribed pieces fields
                'prescribed_pieces' => $request->prescribed_pieces,
                'times_per_day' => $request->times_per_day,
                'start_day' => $request->start_day,
                'first_dose_time' => $request->first_dose_time,
                'first_dose_period' => $request->first_dose_period,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medication alert updated successfully',
                'alert' => $alert->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update medication alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified medication alert.
     */
    public function destroy($id)
    {
        try {
            \Log::info('Attempting to delete medication alert', ['id' => $id]);

            $alert = MedicationAlert::findOrFail($id);

            \Log::info('Found medication alert', [
                'id' => $alert->id,
                'medication_name' => $alert->medication_name,
            ]);

            $alert->delete();

            \Log::info('Successfully deleted medication alert', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Medication alert deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Medication alert not found', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Medication alert not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to delete medication alert', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete medication alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
