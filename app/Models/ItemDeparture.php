<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDeparture extends Model
{
    protected $table = 'item_departures';

    protected $fillable = ['date', 'item_master', 'prev_stock', 'departure_qnty', 'balance_qnty', 'notes'];

    protected $casts = [
        'date' => 'date',
        'prev_stock' => 'decimal:2',
        'departure_qnty' => 'decimal:2',
        'balance_qnty' => 'decimal:2',
    ];
}
