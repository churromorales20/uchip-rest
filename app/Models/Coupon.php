<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'code',
        'valid_from',
        'valid_to',
        'discount_type',
        'amount',
        'minimum_purchase',
        'max_coupons',
        'user_behavior',
        'available_to',
        'general_status',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function getOrdersQtyAttribute()
    {
        return $this->orders()->count();
    }
    public function getDiscountTypeAttribute($value)
    {
        return (int) $value;
    }
    public function getUserBehaviorAttribute($value)
    {
        return (int) $value;
    }
    public function getAvailableToAttribute($value)
    {
        return (int) $value;
    }
    public function getMinimumPurchaseAttribute($value)
    {
        return floatval($value);
    }
    public function scopeCodeActive($query, $code){
        $query->where('code', $code)
            ->whereDate('valid_from', '<=', Carbon::now())
            ->whereDate('valid_to', '>=', Carbon::now());
        return $query;
    }
}
