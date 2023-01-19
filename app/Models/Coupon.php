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
    public function scopeCodeActive($query, $code){
        $query->where('code', $code)
            ->where('general_status', 1)
            ->whereDate('valid_from', '<=', Carbon::now())
            ->whereDate('valid_to', '>=', Carbon::now());
        return $query;
    }
}
