<?php 
namespace App\Http\Controllers\Traits\Orders;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

trait OrdersTrait {
    private function applyCouponCode(&$order_data, $coupon){
        $order_data['coupon_id'] = $coupon['id'];
        //$coupon['discount_type'] 1:percentage, 2:fixed_amount
        $order_data['coupon_discount'] = $coupon['discount_amount'];
        $order_data['total'] -= $order_data['coupon_discount'];
    }
    private function FetchOrderAddtionals($additionals){
        $adds = [];
        $total_adds = 0;
        foreach ($additionals as $key => $additional) {
            $total_add_item = $additional['qty'] * $additional['price'];
            $total_adds += $total_add_item;
            $adds[] = [
                't' => $total_add_item,
                'q' => $additional['qty'],
                'p' => $additional['price'],
                'm' => $additional['name']
            ];
        }
        return [
            'items' => $adds,
            'total' => $total_adds
        ];
    }
    private function PreFetchOrderFromItems($items_request){
        $order_total = 0;
        $order_total_normal = 0;
        $order_total_items = 0;
        $order_total_additionals = 0;
        $items_qty = 0;
        $order_items = [];
        $valid = true;
        $unavailables = [];
        foreach ($items_request as $key => $item) {
            if(empty($item['id']) || !is_numeric($item['id'])){
                return [
                    'status'=>'error', 
                    'error' => true,
                    'code' => 21, //INVALID ITEM
                    'product_id' => $item['id']
                ];
            }elseif(!is_numeric($item['qty']) || $item['qty'] < 1 || $item['qty'] > 10){ //TODO DEFINE LIMIT IN CONFIG
                return [
                    'status'=>'error', 
                    'error' => true,
                    'code' => 20, //INVALID QUANTITY
                    'product_id' => $item['id']
                ];
            }
            if($product_db = Product::find($item['id'])){
                $item['product'] = $product_db->toArray();
                $sell_price = !empty($item['product']['price_discount']) ? $item['product']['price_discount'] : $item['product']['price'];
                $total_item_base = $sell_price * $item['qty'];
                $total_item_normal = $item['product']['price'] * $item['qty'];
                //$total_item_discount = ($total_item_normal) - $total_item_base;
                //$additionals['total'] = 0;
                $order_total_items += $total_item_base;
                $additionals = $this->FetchOrderAddtionals($item['additionals']);
                //dd($total_item_base, $sell_price, $item['qty']);
                $qtyaddtotal = $additionals['total'] * $item['qty'];
                $order_total_additionals += $qtyaddtotal;
                $order_total += $total_item_base + ($qtyaddtotal);
                $order_total_normal += $total_item_normal + $qtyaddtotal;
                $items_qty += $item['qty'];
                $order_items[] = [
                    'unit_price' => $sell_price,
                    'product_id' => $item['id'],
                    'comments' => empty($item['comments']) ? '' : $item['comments'],
                    'qty' => $item['qty'],
                    'total_sell' => $total_item_base + $additionals['total'],
                    'total_item' => $total_item_base,
                    'total_discount' => ($total_item_normal - $total_item_base) * $item['qty'],
                    'total_additionals' => $additionals['total'] * $item['qty'],
                    'additionals' => json_encode($additionals['items'])
                ];
            }else{
                $valid = false;
                $unavailables[] = $item['id'];
            }
        }
        return $valid === true ? [
            'total' => $order_total,
            'lines' => count($items_request),
            'items_qty' => $items_qty,
            'total_items' => $order_total_items,
            'total_normal' => $order_total_normal,
            'total_discount' => $order_total_normal - $order_total,
            'items' => $order_items,
            'total_additionals' => $order_total_additionals
        ] : [
            'status'=>'error', 
            'error' => true,
            'code' => 22, //INVALID QUANTITY
            'unavailables' => $unavailables
        ];
    }
    private function GetActiveOrdersFromIdentifier($order_identifier){
        $orders = Order::whereHas('userOrderIdentifier', function ($query) use ($order_identifier) {
            $query->where('order_identifier', $order_identifier);
        })
        ->whereIn('status', ['pending', 'accepted'])
        ->with('products')
        ->get()
        ->append([
            'time_created_at',
            'locator',
            'formatted_created_at', 
        ])
        ->toArray();
        return json_decode(json_encode($orders));
    }
    private function InsertOrderTransaction($order_data){
        DB::beginTransaction();
        try {
            $order = Order::create($order_data);
            $order->items()->createMany($order_data['items']);
            DB::commit();
            return [
                'order_id' => $order->id,
                'order_rendered' => json_decode(json_encode(Order::where('id', $order->id)
                                                            ->with('products')
                                                            ->first()
                                                            ->append([
                                                                'time_created_at',
                                                                'locator',
                                                                'formatted_created_at'
                                                            ])))
            ];
        }catch (Exception $e) {
            DB::rollback();
            return [
                'error' => $e
            ];
        }
    }
}
?>