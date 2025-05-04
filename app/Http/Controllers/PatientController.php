<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientUserRegisterRequest;
use App\Models\PatientUser;
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
        return view('patient.checkup', compact('patient'));
    }
}
