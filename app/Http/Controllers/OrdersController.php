<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\Category;
class OrdersController extends Controller
{
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
    public function Create(Request $request){
        $items = $request->json()->get("items");
        $user = $request->json()->get("user");
        $tip_amount = $request->json()->get("tip_amount");
        $coupon_code = $request->json()->get("coupon_code");
        $payment_method = $request->json()->get("payment_method");
        $delivery_address = $request->json()->get("delivery_address");
        if(!empty($coupon_code)){
            
        }
        //TODO: VALIDATE ORDER
        Order::create([
            'user_data' => $user,
            'delivery_address' => $delivery_address,
        ]);
        //dd($request->json()->get("items"));
        sleep(4);
        return response()->json([
            'status'=>'success', 
            'order_id' => 1,
            'items'=> $items
        ]);
    }
}
