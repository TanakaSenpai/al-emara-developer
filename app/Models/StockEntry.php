<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $table = 'stock_entries';

    protected $fillable = ['date', 'supplier_master', 'ref_party_chalan', 'date_of_party_chalan', 'sub_total', 'notes'];

    protected $casts = [
        'date' => 'date',
        'date_of_party_chalan' => 'date',
        'sub_total' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(StockEntryDetail::class);
    }
}
