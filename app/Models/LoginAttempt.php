<?php
// app/Models/LoginAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $table = 'login_attempts';

    protected $fillable = [
        'email',
        'status',
        'ip_address',
        'user_agent',
    ];
}