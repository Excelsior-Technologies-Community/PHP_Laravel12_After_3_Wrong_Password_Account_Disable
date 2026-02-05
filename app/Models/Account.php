<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'accounts';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'failed_attempts',
        'locked_until',
    ];
}
