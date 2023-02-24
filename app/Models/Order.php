<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
        'total_additionals',
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
    public function live($query){
        $query->whereDate('created_at', today());
        return $query;
    }
    public function getDeliveryAddressAttribute($value){
        return json_decode($value);
    }
    public function getUserDataAttribute($value){
        return json_decode($value);
    }
    public function items()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 
                        'orders_products', 
                        'order_id', 
                        'product_id')
                    ->withPivot(
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
                    );
    }
    public function getTotalAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getTotalAdditionalsAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getTotalDeliveryAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getTotalDiscountAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getTotalItemsAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getTotalTaxAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getTotalTipAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getCouponDiscountAttribute($value){
        return $value === null ? null : floatval($value);
    }
    public function getPaymentStatusAttribute($value){
        return $value === null ? null : intval($value);
    }
    public function getFormattedCreatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d H:i:s');
    }
    public function getTimeCreatedAtAttribute(){
        return $this->created_at->timestamp;
    }
    public function scopeGetLiveItems($query){
        $query->whereDate('created_at', today())
              ->with('products')
              ->orderBy('id', 'asc');
        return $query;
    }
}
