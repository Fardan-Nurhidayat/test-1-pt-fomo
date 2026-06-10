<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'products_id',
        'quantity',
        'reserved_quantity',
        'version',
        'locked_until',
        'updated_by_user_id'
    ];
}
