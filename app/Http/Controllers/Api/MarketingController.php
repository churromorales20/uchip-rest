<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteConfig;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
class MarketingController extends Controller
{
    public function GetCoupons(Request $request){
        $coupons = Coupon::withTrashed()->orderBy('id', 'desc')->get()->append('orders_qty');
        return response()->json([
            'status'=>'success', 
            'coupons'=> $coupons
        ]);
    }
    public function CheckCoupon(Request $request){
        $coupon_code = $request->json()->get("coupon_code");
        $available = Cache::rememberForever('_check_coupon_code_' . $coupon_code . '_', function() use ($coupon_code){
            return Coupon::withTrashed()->where('code', $coupon_code)->count() > 0 ? false : true;
        });
        return response()->json([
            'status'=>'success', 
            'available'=> $available
        ]);
    }
    public function CreateCoupon(Request $request){
        $coupon = $request->json()->get("coupon");
        $coupon_created = Coupon::create([
            'title' => $coupon['title'],
            'code' => $coupon['code'],
            'general_status' => 1,
            'valid_from' => $coupon['dates']['from'],
            'valid_to' => $coupon['dates']['to'],
            'discount_type' => strval($coupon['discount_type']),
            'amount' => $coupon['amount'],
            'minimum_purchase' => $coupon['minimun_purchase'],
            'max_coupons' => $coupon['max_coupons'],
            'user_behavior' => strval($coupon['user_behavior']),
            'available_to' => strval($coupon['available_to']),
        ]);
        return response()->json([
            'status'=>'success', 
            'coupon'=> Coupon::find($coupon_created->id)->append('orders_qty')  
        ]);
    }
}
