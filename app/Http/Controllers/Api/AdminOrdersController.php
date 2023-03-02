<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\Orders\OrdersLiveTrait;
use App\Helpers\OrdersHelper;
use App\Helpers\CouponsHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Faker\Factory;
use App\Models\Order;
use App\Events\OrderUpdated;
class AdminOrdersController extends Controller
{
    use OrdersLiveTrait;
    public function changeOrderStatus(Request $request){
        $change = $request->json()->get("change");
        switch ($change['type']) {
            case 'order_status':
                $status_col = 'status';
                $status_val = $change['status'];
                break;
            case 'payment_status':
                $status_col = 'payment_status';
                $status_val = $change['status'];
                break;
            case 'store_status':
                $status_col = 'store_status';
                $status_val = $change['status'];
                break;
        }
        if(isset($status_col)){
            $order_id = $request->json()->get("id");
            Order::where('id', $order_id)->update([$status_col => $status_val]);
            OrderUpdated::dispatch($order_id, $change);
            return response()->json([
                'status'=>'success', 
            ]);
        }
        return response()->json([
            'status'=>'error', 
        ]);
    }
    public function getLiveOrders(Request $request){
        /*$orders = [];
        $faker = Factory::create();
        $status_order = ['pending', 'accepted', 'ended', 'rejected'];
        $payment_methods = ['Efectivo', 'Plin', 'Yape', 'Transferencia'];
        for ($i=0; $i < 35; $i++) { 
            $id = $faker->numberBetween(20000, 99999);
            $created_at = $faker->dateTimeBetween('-40 minutes', 'now')->format('Y-m-d H:i:s');
            $products = [];
            $lines = rand(1,12);
            $total_qty = 0;
            for ($j=0; $j < $lines; $j++) { 
                $qty = rand(1,9);
                $total_qty += $qty; 
                $unit_p = $faker->randomFloat(2, 13, 20);
                $total_adds = rand(0, 8);
                $products[] = [
                    'name' => $faker->words(2, true),
                    'id' => $faker->unique()->uuid,
                    'qty' => $qty,
                    'unit_price' => $unit_p,
                    'total_additionals' => $total_adds,
                    'comments' => implode(' ', $faker->words(rand(0, 10))),
                    'additionals' => [
                        [
                            "m"=> "Adicional 1", 
                            "p"=> "2.00", 
                            "q"=> 1, 
                            "t"=> 2
                        ]
                    ]
                ];
            }
            $orders[] = [
                'id' => $id,
                'locator' => 'WA-' . $id,
                'user_data' => [
                    "name"=> $faker->name, 
                    "email"=> $faker->email, 
                    "phone"=> $faker->phoneNumber
                ],
                'items_qty' => $total_qty,
                'total' => $faker->randomFloat(2, 16, 60),
                'total_items' => $faker->randomFloat(2, 12, 45),
                'total_discount' => $faker->randomFloat(2, 0, 8),
                'total_additionals' => $faker->randomFloat(2, 0, 12),
                'total_delivery' => $faker->randomFloat(2, 0, 16),
                'total_tip' => rand(0,8),
                'delivery_address' => [
                    "text" => $faker->address, 
                ],
                'products' => $products,
                'status' => $faker->randomElement($status_order),
                'payment_method' => $faker->randomElement($payment_methods),
                'store_status' => 'preparing',
                'payment_status' => rand(0,2),
                'time_created_at' => strtotime($created_at),
                'created_at' => $created_at,
            ];
        }
        //sleep(6);*/

        $orders = $this->getLive();
        //print_r($orders); die;
        return response()->json([
            'status'=>'success', 
            'orders' => $orders
        ]);
    }
}
