<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $table = 'flash_sales';

    protected $fillable = [
        'products_id',
        'title',
        'description',
        'discount_value',
        'status',
        'start_time',
        'end_time',
    ];
}
