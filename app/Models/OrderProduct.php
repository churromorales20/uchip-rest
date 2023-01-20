<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderProduct extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'orders_products';
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'unit_price',
        'total_sell',
        'total_item',
        'total_discount',
        'total_additionals',
        'additionals',
        'comments'
    ];
    
}
