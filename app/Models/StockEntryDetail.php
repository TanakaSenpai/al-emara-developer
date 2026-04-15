<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntryDetail extends Model
{
    protected $table = 'stock_entry_details';

    protected $fillable = ['stock_entry_id', 'item_master', 'old_stock', 'qnty', 'new_stock', 'purchase_rate', 'total_price'];

    protected $casts = [
        'old_stock' => 'decimal:2',
        'qnty' => 'decimal:2',
        'new_stock' => 'decimal:2',
        'purchase_rate' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function stockEntry()
    {
        return $this->belongsTo(StockEntry::class);
    }
}
