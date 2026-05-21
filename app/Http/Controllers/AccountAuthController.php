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
    public function register()
    {
        return view('register');
    }

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

    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $account = Account::where('email', $request->email)->first();

        if (!$account) {
            LoginAttempt::create([
                'email' => $request->email,
                'status' => 'failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => 'Invalid Email']);
            return back()->with('error', 'Invalid Email');
        }

        if ($account->locked_until && Carbon::now()->lessThan($account->locked_until)) {
            $minutes = Carbon::now()->diffInMinutes($account->locked_until);
            $minutes = $minutes == 0 ? 1 : $minutes;
            
            $msg = "Your account has been locked. Please try again after $minutes minute(s).";
            
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $msg]);
            return back()->with('error', $msg);
        }

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
              
                $account->locked_until = Carbon::now()->addMinutes(1);
                $account->failed_attempts = 0;
                $account->save();
                
              
                $msg = 'Account locked for 1 minute due to multiple failed attempts!';
                if ($request->ajax()) return response()->json(['status' => 'error', 'message' => $msg]);
                return back()->with('error', $msg);
            }

            $account->save();
            $msg = "Wrong Password. $remaining attempts left.";
            
            if ($request->ajax()) return response()->json(['status' => 'warning', 'message' => $msg]);
            return back()->with('error', $msg);
        }

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

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Login Successful! Redirecting...',
                'redirect' => url('/dashboard')
            ]);
        }

        return redirect('/dashboard');
    }

    public function dashboard()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }

        return view('dashboard');
    }

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

    public function blockedAccounts()
    {
        $blockedAccounts = Account::whereNotNull('locked_until')->where('locked_until', '>', Carbon::now())->get();
        return view('admin_blocked_accounts', compact('blockedAccounts'));
    }

    public function unblockAccount($id)
    {
        $account = Account::findOrFail($id);
        $account->failed_attempts = 0;
        $account->locked_until = null;
        $account->save();

        return back()->with('success', 'Account unblocked successfully');
    }
}