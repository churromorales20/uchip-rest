<?php 
namespace App\Http\Controllers\Traits\Orders;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Helpers\OrdersHelper;
trait OrdersLiveTrait {
    
    private function getLive(){

        $render = function($order){
            return OrdersHelper::renderAdminOrder($order);
        };
        return array_map(function($order) use ($render){
            return $render($order);
        }, json_decode(json_encode(Order::getLiveItems()
                                    ->get()
                                    ->append([
                                        'time_created_at',
                                        'formatted_created_at'
                                    ])
                                    ->toArray()
                        )));
    }
}
?>