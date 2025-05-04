<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        $role_datas   = Role::select('id', 'name')->where('name', '!=', 'Administrator')->get();
        return view('admin.register', compact('role_datas'));
    }

    public function store(RegisterRequest $request)
    { 
        $data = $request->except(['_token']);
        $data['username'] = str_replace(' ', '', strtolower($data['first_name']));
        $data['password'] = bcrypt($data['license_no']);
        $user = User::create($data);

        if ($request->input('role', false)) {
            $user->assignRole($data['role']);
        }

        return redirect()->route('register.index');
    }
}
