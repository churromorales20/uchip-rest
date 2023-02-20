<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\Orders\OrdersTrait;
use App\Helpers\OrdersHelper;
use App\Helpers\CouponsHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Faker\Factory;
class AdminOrdersController extends Controller
{
    //use OrdersTrait;
    public function getLiveOrders(Request $request){
        $orders = [];
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
                'total' => 34,
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
        //sleep(6);
        return response()->json([
            'status'=>'success', 
            'orders' => $orders
        ]);
    }
}
