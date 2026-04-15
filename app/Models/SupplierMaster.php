<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaster extends Model
{
    protected $table = 'supplier_masters';

    protected $fillable = ['supplier_name', 'mobile', 'address', 'due_balance'];

    protected $casts = [
        'due_balance' => 'decimal:2',
    ];
}
