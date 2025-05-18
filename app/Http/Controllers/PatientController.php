<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosisRequest;
use App\Http\Requests\PatientUserRegisterRequest;
use App\Models\Diagnosis;
use App\Models\PatientUser;
use App\Models\User;
use Illuminate\Http\Request;

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
            $data['username'] = str_replace(' ', '', strtolower($data['first_name']));
            $data['password'] = bcrypt($data['last_name']);
            $user = PatientUser::create($data);

            return redirect()->route('patient.index');
        } catch (\Throwable $th) {
            dd($th);
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
            $data['user_id'] = $patient->id;
            if(isset($data['txtarea_remarks']))
            {
                $data['remarks']['others'] = $data['txtarea_remarks'];
            }
            $data['remarks'] = json_encode($data['remarks']);
            $user = Diagnosis::create($data);

            return redirect()->route('patient.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
