<?php
// app/Http/Controllers/AccountAuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AccountAuthController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6',
        ]);

        Account::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Account Created Successfully! Please login.');
    }

    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $account = Account::where('email', $request->email)->first();

        // Check if account exists
        if (!$account) {
            $this->logAttempt($request->email, 'failed', $request);
            
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Invalid Email Address']);
            }
            return back()->with('error', 'Invalid Email Address')->withInput();
        }

        // Check if account is locked
        if ($account->isLocked()) {
            $remainingMinutes = $account->getRemainingLockMinutes();
            $message = "Your account is locked due to multiple failed attempts. Please try again after {$remainingMinutes} minute(s).";
            
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $message]);
            }
            return back()->with('error', $message);
        }

        // Check password
        if (!Hash::check($request->password, $account->password)) {
            // Increment failed attempts
            $account->failed_attempts++;
            $remainingAttempts = max(0, 3 - $account->failed_attempts);
            
            // Lock account after 3 failed attempts
            if ($account->failed_attempts >= 3) {
                $account->locked_until = Carbon::now()->addMinutes(10);
                $account->failed_attempts = 0;
                $account->save();
                
                $this->logAttempt($request->email, 'failed', $request);
                $message = 'Account locked for 10 minutes due to 3 failed login attempts!';
                
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => $message]);
                }
                return back()->with('error', $message);
            }
            
            $account->save();
            $this->logAttempt($request->email, 'failed', $request);
            $message = "Wrong password! {$remainingAttempts} attempt(s) remaining.";
            
            if ($request->ajax()) {
                return response()->json(['status' => 'warning', 'message' => $message]);
            }
            return back()->with('error', $message)->withInput();
        }

        // Successful login - reset all security measures
        $account->failed_attempts = 0;
        $account->locked_until = null;
        $account->save();
        
        $this->logAttempt($request->email, 'success', $request);
        
        Session::put('account_id', $account->id);
        Session::put('account_name', $account->name);
        Session::put('account_email', $account->email);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful! Redirecting...',
                'redirect' => url('/dashboard')
            ]);
        }

        return redirect('/dashboard')->with('success', 'Welcome back, ' . $account->name . '!');
    }

    private function logAttempt($email, $status, $request)
    {
        LoginAttempt::create([
            'email' => $email,
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    public function dashboard()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }

        $account = Account::find(Session::get('account_id'));
        
        return view('dashboard', compact('account'));
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }

    public function loginAttempts()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }
        
        $logs = LoginAttempt::orderBy('created_at', 'desc')->get();
        return view('logs', compact('logs'));
    }

    public function blockedAccounts()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }
        
        $blockedAccounts = Account::whereNotNull('locked_until')
            ->where('locked_until', '>', Carbon::now())
            ->orderBy('locked_until', 'desc')
            ->get();
            
        return view('admin_blocked_accounts', compact('blockedAccounts'));
    }

    public function unblockAccount($id)
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }
        
        $account = Account::findOrFail($id);
        $account->failed_attempts = 0;
        $account->locked_until = null;
        $account->save();

        return back()->with('success', 'Account for ' . $account->email . ' has been unblocked successfully!');
    }

    public function showProfile()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }
        
        $account = Account::find(Session::get('account_id'));
        return view('profile', compact('account'));
    }

    public function updateProfile(Request $request)
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }
        
        $account = Account::find(Session::get('account_id'));
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,' . $account->id,
        ]);
        
        $account->name = $request->name;
        $account->email = $request->email;
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $account->password = Hash::make($request->password);
        }
        
        $account->save();
        
        Session::put('account_name', $account->name);
        Session::put('account_email', $account->email);
        
        return back()->with('success', 'Profile updated successfully!');
    }
}