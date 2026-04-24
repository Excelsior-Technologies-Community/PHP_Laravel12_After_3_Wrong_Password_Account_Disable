<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\LoginAttempt;

class AccountAuthController extends Controller
{
    // Show Register Page
    public function register()
    {
        return view('register');
    }

    // Register Logic
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6',
        ]);

        Account::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Account Created Successfully');
    }

    // Show Login Page
    public function login()
    {
        return view('login');
    }

    // LOGIN LOGIC WITH LOG TRACKING
    public function loginPost(Request $request)
    {
        $account = Account::where('email', $request->email)->first();

        // EMAIL NOT FOUND
        if (!$account) {

            LoginAttempt::create([
                'email' => $request->email,
                'status' => 'failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('error', 'Invalid Email');
        }

        // CHECK LOCK
        if ($account->locked_until && Carbon::now()->lessThan($account->locked_until)) {

            $minutes = Carbon::now()->diffInMinutes($account->locked_until);

            return back()->with(
                'error',
                "Your account has been locked after 3 failed login attempts for security reasons. Please try again after $minutes minutes."
            );
        }

        // WRONG PASSWORD
        if (!Hash::check($request->password, $account->password)) {

            $account->failed_attempts++;

            $remaining = max(0, 3 - $account->failed_attempts);

            LoginAttempt::create([
                'email' => $request->email,
                'status' => 'failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($account->failed_attempts >= 3) {
                $account->locked_until = Carbon::now()->addMinutes(10);
                $account->failed_attempts = 0;
                $account->save();

                return back()->with('error', 'Account locked for 10 minutes!');
            }

            $account->save();

            return back()->with('error', "Wrong Password. $remaining attempts left.");
        }

        //  SUCCESS LOGIN
        $account->failed_attempts = 0;
        $account->locked_until = null;
        $account->save();

        LoginAttempt::create([
            'email' => $request->email,
            'status' => 'success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Session::put('account_id', $account->id);

        return redirect('/dashboard');
    }

    // Dashboard
    public function dashboard()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }

        return view('dashboard');
    }

    // Logout
    public function logout()
    {
        Session::forget('account_id');
        return redirect('/login');
    }

    public function loginAttempts()
    {
        $logs = \App\Models\LoginAttempt::orderBy('id', 'asc')->get();
        return view('logs', compact('logs'));
    }
}
