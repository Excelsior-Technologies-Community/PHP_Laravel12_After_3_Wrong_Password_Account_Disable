<?php
// app/Models/Account.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'email',
        'password',
        'failed_attempts',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    public function isLocked()
    {
        return $this->locked_until && now()->lessThan($this->locked_until);
    }

    public function getRemainingLockMinutes()
    {
        if (!$this->isLocked()) {
            return 0;
        }
        
        return now()->diffInMinutes($this->locked_until, false);
    }
}