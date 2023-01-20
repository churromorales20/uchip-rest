<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\Orders\OrdersTrait;
use App\Helpers\OrdersHelper;
use App\Helpers\CouponsHelper;
class OrdersController extends Controller
{
    use OrdersTrait;
    public function __construct(){
        
    }
    public function GetConfiguration(Request $request){
        return response()->json([
            'status'=>'success', 
            'config'=> [
                'tips' => [50,60],
                'active_discounts' => true,
                'payments' => [
                    'Yape',
                    'Plin',
                    'Efectivo'
                ]
            ]
        ]);
    }
    public function PreCheck(Request $request){
        sleep(2);
        return response()->json([
            'status'=>'success',
        ]);
    }
    public function CouponCheck(Request $request){
        $coupon_code = $request->input('coupon_code');
        $user_email = $request->input('user_email');
        $order_total = $request->input('order_total');
        $coupon = CouponsHelper::ValidateCouponCode($coupon_code, $user_email, $order_total);
        if(!is_array($coupon) || isset($coupon['error_code'])){
            return response()->json([
                'status'=>'error', 
                'code' => !is_array($coupon) ? 0 : $coupon['error_code']
            ]);
        }else{
            return response()->json([
                'status'=>'success',
                'discount_amount'=> $coupon['discount_amount'],
            ]);
        }
    }
    public function Create(Request $request){
        if(OrdersHelper::CurentlyAccepting()){  
            $items_request = $request->json()->get("items");
            $user = $request->json()->get("user");
            $tip_amount = $request->json()->get("tip_amount");
            $coupon_code = $request->json()->get("coupon_code");
            $payment_method = $request->json()->get("payment_method");
            $delivery_address = $request->json()->get("delivery_address");
            $comments = $request->json()->get("general_comments");
            $order_data = $this->PreFetchOrderFromItems($items_request);
            $error = 0;
            //dd($order_data);
            /*customer = CustomerGuest::firstOrCreate(
                ['email' => $user['email']],
                [
                    'whole_name' => $user['name'],
                    'phone' => $user['phone'],
                    'identifier' => bin2hex(random_bytes(35)),
                ]
            );*/
            if(!empty($coupon_code)){
                //UNCOMMENT IN THE FUTURE
                //$coupon = $this->validateCouponCode($coupon_code, $customer->email,$order_data['total']);
                $coupon = CouponsHelper::ValidateCouponCode($coupon_code, $user['email'],$order_data['total']);
                if(!is_array($coupon) || isset($coupon['error_code'])){
                    return response()->json([
                        'status'=>'error', 
                        'code' => !is_array($coupon) ? 0 : $coupon['error_code']
                    ]);
                }else{
                    $this->applyCouponCode($order_data, $coupon);
                }
            }
            $order_data['customer_guest'] = 1;
            $order_data['user_id'] = 0;
            $order_data['user_data'] = json_encode($user);
            $order_data['delivery_address'] = json_encode($delivery_address);
            $order_data['payment_method'] = $payment_method;
            $order_data['comments'] = empty($comments) ? '' : $comments;
            if(is_numeric($tip_amount)){
                $order_data['total_tip'] = $tip_amount;
                $order_data['total'] += $tip_amount;
            }
            $transaction_result = $this->InsertOrderTransaction($order_data);
            if(!is_array($transaction_result) || isset($transaction_result['error'])){
                //dd($transaction_result); //Error on order creation
                return response()->json([
                    'status'=>'error', 
                    'code' => 16
                ]);
            }
            
            return response()->json([
                'status'=>'success', 
                'order_id' => $transaction_result['order_id'],
            ]);
        }
    }
}
