<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    protected $table = 'budget_plans';

    protected $fillable = ['date', 'partner_master', 'budget_details', 'charge_amount', 'notes'];

    protected $casts = [
        'date' => 'date',
        'charge_amount' => 'decimal:2',
    ];
}
