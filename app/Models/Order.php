<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_guest',
        'user_id',
        'lines',
        'items_qty',
        'user_data',
        'total',
        'total_items',
        'total_discount',
        'total_tax',
        'total_delivery',
        'delivery_address',
        'payment_method',
        'coupon_id',
        'coupon_discount',
        'total_tip',
        'status',
        'store_status',
        'payment_status',
        'comments',
    ];
    public function scopeWithEmailAndCoupon($query, $email, $coupon_id){
        $query->where('user_data', 'LIKE', '%' . $email . '%')
            ->where('coupon_id', $coupon_id);
        return $query;
    }
    public function scopeOfEmail($query, $email){
        $query->where('user_data', 'LIKE', '%' . $email . '%');
        return $query;
    }
    public function items()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
