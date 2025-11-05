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
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patient_users,id',
            'time' => 'required|string',
            'period' => 'required|in:AM,PM',
            'medication_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'is_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $alert = MedicationAlert::create([
                'patient_id' => $request->patient_id,
                'time' => $request->time,
                'period' => $request->period,
                'medication_name' => $request->medication_name,
                'remarks' => $request->remarks,
                'is_enabled' => $request->is_enabled ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medication alert created successfully',
                'alert' => $alert,
            ], 201);
        } catch (\Exception $e) {
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
            $alert = MedicationAlert::findOrFail($id);
            $alert->delete();

            return response()->json([
                'success' => true,
                'message' => 'Medication alert deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete medication alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
