<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'products_id',
        'quantity',
        'reserved_quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'products_id');
    }
}
