<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['account_number', 'description', 'balance_amount'];

    protected $casts = [
        'balance_amount' => 'decimal:2',
    ];
}
