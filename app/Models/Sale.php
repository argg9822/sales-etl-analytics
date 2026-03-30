<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'import_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'order_id',
        'category',
        'sale_date',
        'customer_id',
        'country',
        'discount',
        'total'
    ];
}
