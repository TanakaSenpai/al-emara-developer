<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerCollection extends Model
{
    protected $table = 'partner_collections';

    protected $fillable = ['date', 'partners', 'budget_plan', 'account_master', 'total_due_balance', 'total_paid_amount', 'extra_charges', 'net_amount', 'notes'];

    protected $casts = [
        'date' => 'date',
        'total_due_balance' => 'decimal:2',
        'total_paid_amount' => 'decimal:2',
        'extra_charges' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];
}
