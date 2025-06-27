<?php

namespace App\Http\Controllers;

use App\Classes\UserStatus;
use App\Http\Requests\RegisterRequest;
use App\Mail\VerifyEmail;
use App\Models\DoctorSchedule;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('id', '!=', '1')->get();
        return view('admin.admin-index', compact('admins'));
    }

    public function create()
    {
        $role_datas   = Role::select('id', 'name')->where('name', '!=', 'Administrator')->get();
        return view('admin.register', compact('role_datas'));
    }

    public function store(RegisterRequest $request)
    {
        $data = $request->except(['_token']);
        $passwordNo       = rand(1000, 9999);
        $data['username'] = $data['email'];
        $data['password'] = '@'. str_replace(' ', '', ucfirst(strtolower($data['first_name']))) .''.$passwordNo;
        $data['status']   = UserStatus::ACTIVATED;
        if ($request->input('schedule', false)) {
            $data['schedule'] = json_encode($data['schedule']);
        }
        if ($request->hasFile('profile_pic')) {
            $fileName = $request->file('profile_pic')->hashName();
            $path = $request->file('profile_pic')->storeAs('images', $fileName, 'public');
            $data["profile_pic"] = '/storage/' . $path;
        }
        $user = User::create($data);
        if ($request->input('schedule', false)) {
            foreach($request->schedule as $index => $schedule)
            {
                $data_schedule = [
                    'doctor_id' => $user->id,
                    'day_of_week' => $index,
                    'start_time' => Carbon::parse($schedule['time_in'])->format('h:i A') ?? '',
                    'end_time' => Carbon::parse($schedule['time_out'])->format('h:i A') ?? '',
                ];

                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $user->id,
                        'day_of_week' => $index,
                    ],
                    $data_schedule);
            }
        }
        if ($request->input('role', false)) {
            $user->assignRole($data['role']);
        }

        Mail::to($user->email)->send(new VerifyEmail($data));

        return redirect()->route('admin.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $role_datas   = Role::select('id', 'name')->where('name', '!=', 'Administrator')->get();
        return view('admin.edit', compact('user', 'role_datas'));
    }

    public function update(RegisterRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->except(['token']));

        if ($request->input('role', false)) {
            $user->assignRole($request['role']);
        }
        return redirect()->route('admin.index');
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $data = $request->except(['token']);
        if ($request->hasFile('profile_pic')) {
            $fileName = $request->file('profile_pic')->hashName();
            $path = $request->file('profile_pic')->storeAs('images', $fileName, 'public');
            $data["profile_pic"] = '/storage/' . $path;
        }
        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profile Updated Successfully');
    }

    public function profile()
    {
        $user = Auth::user();
        $role_datas   = Role::select('id', 'name')->get();
        return view('admin.profile', compact('user', 'role_datas'));
    }
}
