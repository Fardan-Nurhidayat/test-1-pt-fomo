<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'flash_sale_id',
        'order_number',
        'status',
        'total_price',
        'discount_applied',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function flashSale(): BelongsTo
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'orders_id');
    }
}
