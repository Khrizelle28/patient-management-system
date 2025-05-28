<?php

namespace App\Http\Controllers;

use App\Classes\UserStatus;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::get();
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
        $data['username'] = str_replace(' ', '', strtolower($data['first_name']));
        $data['password'] = bcrypt($data['license_no']);
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

        if ($request->input('role', false)) {
            $user->assignRole($data['role']);
        }

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
}
