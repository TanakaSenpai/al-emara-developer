<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerMaster extends Model
{
    protected $table = 'partner_masters';

    protected $fillable = ['partner_name', 'mobile', 'address', 'due_amount', 'extra_amount', 'paid_amount', 'total_charges', 'notes'];

    protected $casts = [
        'due_amount' => 'decimal:2',
        'extra_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'total_charges' => 'decimal:2',
    ];
}
