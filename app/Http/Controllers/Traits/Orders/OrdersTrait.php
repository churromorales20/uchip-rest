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
        foreach ($items_request as $key => $item) {
            $item['product'] = Product::find($item['id'])->toArray();
            $sell_price = !empty($item['product']['price_discount']) ? $item['product']['price_discount'] : $item['product']['price'];
            $total_item_base = $sell_price * $item['qty'];
            $total_item_normal = $item['product']['price'] * $item['qty'];
            //$total_item_discount = ($total_item_normal) - $total_item_base;
            //$additionals['total'] = 0;
            $order_total_items += $sell_price;
            $additionals = $this->FetchOrderAddtionals($item['additionals']);
            //dd($total_item_base, $sell_price, $item['qty']);
            $order_total_additionals += $additionals['total'];
            $order_total += $total_item_base + $additionals['total'];
            $order_total_normal += $total_item_normal + $additionals['total'];
            $items_qty += $item['qty'];
            $order_items[] = [
                'unit_price' => $sell_price,
                'product_id' => $item['id'],
                'comments' => $item['comments'],
                'qty' => $item['qty'],
                'total_sell' => $total_item_base + $additionals['total'],
                'total_item' => $total_item_base,
                'total_discount' => $total_item_normal - $total_item_base,
                'total_additionals' => $additionals['total'],
                'additionals' => json_encode($additionals['items'])
            ];
        }
        return [
            'total' => $order_total,
            'lines' => count($items_request),
            'items_qty' => $items_qty,
            'total_items' => $order_total_items,
            'total_normal' => $order_total_normal,
            'total_discount' => $order_total_normal - $order_total,
            'items' => $order_items,
            'total_additionals' => $order_total_additionals
        ];
    }
    private function InsertOrderTransaction($order_data){
        DB::beginTransaction();
        try {
            $order = Order::create($order_data);
            $order->items()->createMany($order_data['items']);
            DB::commit();
            return [
                'order_id' => $order->id
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