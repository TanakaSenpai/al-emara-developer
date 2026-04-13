<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyExpense extends Model
{
    protected $fillable = ['expense_date', 'expense_details', 'expense_amount', 'expense_by', 'voucher_no', 'account_id'];

    protected $casts = [
        'expense_date' => 'date',
        'expense_amount' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
