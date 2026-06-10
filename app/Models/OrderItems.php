<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'orders_id',
        'products_id',
        'quantity',
        'unit_price',
        'subtotal',
        'discount_percentage',
        'discount_amount',
        'is_flash_sale_item',
    ];
}
