<?php
// app/Http/Middleware/CheckAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Session::has('account_id')) {
            return redirect('/login')->with('error', 'Please login to access this page.');
        }
        
        return $next($request);
    }
}