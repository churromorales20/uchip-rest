<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_data',
        'total',
        'total_discount',
        'total_tax',
        'total_delivery',
        'delivery_address',
        'payment_method',
        'coupon_id',
        'coupon_discount',
        'total_tip',
    ];
    /*public function additionals()
    {
        return $this->hasManyThrough(Additional::class, ProductsAdditionals::class,'product_id', 'id', 'id', 'additional_id');
    }*/
}
