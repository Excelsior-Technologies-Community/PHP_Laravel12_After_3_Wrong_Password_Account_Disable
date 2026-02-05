<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

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
            'password' => Hash::make($request->password), // Encrypt password
        ]);

        return redirect('/login')->with('success', 'Account Created Successfully');
    }

    // Show Login Page
    public function login()
    {
        return view('login');
    }

    // 🔐 LOGIN LOGIC WITH TIME-BASED LOCK
    public function loginPost(Request $request)
    {
        $account = Account::where('email', $request->email)->first();

        // Email not found
        if (!$account) {
            return back()->with('error', 'Invalid Email');
        }

        // Check if account is locked
        if ($account->locked_until && Carbon::now()->lessThan($account->locked_until)) {
            return back()->with(
                'error',
                'Your account has been locked after 3 failed login attempts. Please try again after 10 minutes.'
            );
        }

        // Wrong password
        if (!Hash::check($request->password, $account->password)) {

            $account->failed_attempts++;

            // Lock account after 3 wrong attempts
            if ($account->failed_attempts >= 3) {
                $account->locked_until = Carbon::now()->addMinutes(10);
                $account->failed_attempts = 0; // Reset after lock
            }

            $account->save();

            return back()->with('error', 'Wrong Password');
        }

        // Correct login → reset everything
        $account->failed_attempts = 0;
        $account->locked_until = null;
        $account->save();

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
}
