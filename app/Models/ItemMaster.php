<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMaster extends Model
{
    protected $table = 'item_masters';

    protected $fillable = ['item_name', 'purchase_rate', 'stock_balance'];

    protected $casts = [
        'purchase_rate' => 'decimal:2',
        'stock_balance' => 'decimal:2',
    ];
}
