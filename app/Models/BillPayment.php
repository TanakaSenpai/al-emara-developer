<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    protected $table = 'bill_payments';

    protected $fillable = ['date', 'supplier_name', 'account_master', 'last_balance', 'paid_amount', 'paid_by', 'notes'];

    protected $casts = [
        'date' => 'date',
        'last_balance' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];
}
