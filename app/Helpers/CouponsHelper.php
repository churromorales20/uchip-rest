<?php 
namespace App\Helpers;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Order;
class CouponsHelper
{
    public static function ValidateCouponCode($coupon_code, $user_email, $order_amount){
        $code = 10; //ERROR 10, INVALID OR EXPIRED COUPON
        if($coupon = Coupon::codeActive($coupon_code)->first()){
            //dd($coupon, $order_amount);
            if($coupon->user_behavior == 1 && Order::withEmailAndCoupon($user_email, $coupon->id)->count() > 0){
                $code = 11; //ERROR 11 USER PREVIOUSLY USED SAME COUPON
            }
            if($coupon->available_to == 2 && Order::ofEmail($user_email)->count() > 0){
                $code = 12; //ERROR 12 NOT NEW USER
            }
            if($coupon->max_coupons > 0 && Order::where('coupon_id', $coupon->id)->count() > $coupon->max_coupons){
                $code = 14; //ERROR 14 COUPON OUT OF STOCK
            }
            if($coupon->minimum_purchase > 0 && $order_amount < $coupon->minimum_purchase){
                $code = 15; //ERROR 15 MINIMIUN PURCHASE
            }
            if($code === 10){
                return [
                    'id' => $coupon->id,
                    'amount' => $coupon->amount,
                    'discount_type' => $coupon->discount_type,
                    'discount_amount' => $coupon->discount_type == '1' ? 
                                        ($order_amount * ($coupon->amount / 100)) : 
                                        $coupon->amount
                ];
            }
        }
        return [
            'error_code' => $code,
        ];
    }
}

?>