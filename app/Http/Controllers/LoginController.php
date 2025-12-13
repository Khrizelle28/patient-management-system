<?php

namespace App\Http\Controllers;

use App\Classes\UserStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $username;

    public function index()
    {
        return view('login');
    }

    public function __construct()
    {
        $this->username = $this->findUsername();
    }

    public function login(Request $request)
    {
        $atributes = $request->validate([
            $this->username => 'required',
            'password' => 'required',
        ]);

        // Check if user exists and get their status
        $user = User::where('email', $request[$this->username])
            ->orWhere('username', $request[$this->username])
            ->first();

        // Check if user is deactivated before attempting login
        if ($user && $user->status === UserStatus::DEACTIVATED) {
            Auth::logout();

            return back()->withInput()->withErrors([
                'username' => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }

        if (Auth::attempt($atributes)) {
            // Double-check status after authentication
            if (Auth::user()->status === UserStatus::DEACTIVATED) {
                Auth::logout();

                return back()->withInput()->withErrors([
                    'username' => 'Your account has been deactivated. Please contact the administrator.',
                ]);
            }

            $request->session()->regenerate();

            return $this->loginStatus($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $users = User::where('email', $request[$this->username])->orWhere('username', $request[$this->username])->first();

        $errors = [
            'username' => 'Invalid username or email. Please try again.',
        ];

        return back()->withInput()->withErrors($errors);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    protected function loginStatus()
    {
        $user = Auth::user();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
