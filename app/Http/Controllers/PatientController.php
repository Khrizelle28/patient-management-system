<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosisRequest;
use App\Http\Requests\PatientUserRegisterRequest;
use App\Models\Diagnosis;
use App\Models\PatientUser;
use App\Models\User;
use App\Services\SemaphoreSmsServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function index()
    {
        $patients = PatientUser::get();
        return view('patient.index', compact('patients'));
    }

    public function create()
    {
        return view('patient.create');
    }

    public function store(PatientUserRegisterRequest $request)
    {
        try {
            $data = $request->except(['_token']);
            $data['username'] = strtolower(str_replace(' ', '', substr($data['first_name'], 0, 1) . $data['last_name']) . rand(1000, 9999));
            $firstName = ucfirst(strtolower($data['first_name']));
            $birthYear = str_replace('-', '', $data['birthday']);
            $rawPassword = '@'. str_replace(' ', '', $firstName) . '' . $birthYear;
            $data['password'] = bcrypt($rawPassword);
            $user = PatientUser::create($data);
            if(env('ENABLE_SMS_NOTIFICATION', false) == true)
            {
                $this->sendSmsNotification($data, $user, $rawPassword);
            }

            return redirect()->route('patient.checkup', ['id' => $user->id]);
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    public function show($id)
    {
        $patient = PatientUser::find($id);
        return view('patient.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = PatientUser::find($id);
        return view('patient.edit', compact('patient'));
    }

    public function update(PatientUserRegisterRequest $request, $id)
    {
        $patient = PatientUser::find($id);
        $patient->update($request->except(['token']));
        return redirect()->route('patient.index');
    }

    public function checkup($id)
    {
        $patient = PatientUser::find($id);
        $doctors = User::role('Doctor')->get();
        return view('patient.checkup', compact('patient', 'doctors'));
    }

    public function diagnosis(DiagnosisRequest $request, $id)
    {
        try {
            $data = $request->safe()->except(['_token', '_method']);
            $patient = PatientUser::find($id);
            $data['patient_user_id'] = $patient->id;
            if(isset($data['family_histories_other']))
            {
                $data['family_histories']['others'] = $data['family_histories_other'];
            }

            if($request->input('family_histories', false))
            {
                $data['family_histories'] = json_encode($data['family_histories']);
            }

            if($request->input('txtarea_remarks', false))
            {
                $data['remarks'] = $data['txtarea_remarks'];
            }
            $data['comeback_info'] = isset($data['to_come_back']) ? Carbon::parse($data['return_date'])->format('Y-m-d h:i A') : $data['no_return_reason'];
            $user = Diagnosis::create($data);

            return redirect()->route('patient.show', ['id' => $patient->id]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function sendSmsNotification($data, $user, $password)
    {
        try {
            // Add debug logging
            Log::info('Starting SMS notification', [
                'user_id' => $user->id,
                'contact_no' => $data['contact_no'] ?? 'NOT_SET',
                'sms_enabled' => env('ENABLE_SMS_NOTIFICATION', false)
            ]);

            $patientName = $data['first_name'] . ' ' . ucfirst(strtolower($data['last_name']));
            $appName = env('APP_NAME', 'Good Health Clinic');

            $rawPassword = $password ?? $data['password'];

            $message = "Hello {$patientName},\n\n";
            $message .= "Your account has been successfully created at {$appName}.\n\n";
            $message .= "Here are your login details:\n";
            $message .= "Username: {$data['username']}\n";
            $message .= "Password: {$rawPassword}\n\n";
            $message .= "Please change your password after your first login.\n\n";
            $message .= "Thank you,\n{$appName}";

            // Debug: Log the message content and length
            Log::info('SMS message prepared', [
                'message_length' => strlen($message),
                'message_preview' => substr($message, 0, 100) . '...'
            ]);

            // Check if contact number exists
            if (empty($data['contact_no'])) {
                Log::error('Contact number is empty', ['data' => $data]);
                return;
            }

            $smsService = new SemaphoreSmsServices();

            // Debug: Test service configuration
            Log::info('SMS service configuration', [
                'api_key_set' => !empty($smsService->apiKey),
            ]);

            $result = $smsService->sendSms(
                $data['contact_no'],
                $message
            );

            // Enhanced logging
            Log::info('SMS send attempt completed', [
                'user_id' => $user->id,
                'result' => $result,
                'success' => $result['success'] ?? false
            ]);

            if ($result['success']) {
                Log::info('Welcome SMS sent to user', [
                    'user_id' => $user->id,
                    'message_id' => $result['message_id'] ?? null
                ]);
            } else {
                Log::error('Failed to send welcome SMS', [
                    'user_id' => $user->id,
                    'error' => $result['error'] ?? 'Unknown error',
                    'response' => $result['response'] ?? null
                ]);
            }

        } catch (\Exception $e) {
            Log::error('SMS notification exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

}
