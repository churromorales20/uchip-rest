<?php 
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteConfig;
use Carbon\Carbon;
class OrdersHelper
{
    public static function IsJobTime(){
        $job_time = SiteConfig::where('key_config', 'job_time')->first();
        $day_index = date('w');
        foreach ($job_time->value[$day_index] as $key => $time) {
            //dd(date("Y-m-d " . $time[0] . ":00"));
            $init = Carbon::createFromTimeString(date("Y-m-d " . $time[0] . ":00"));
            
            $end = Carbon::createFromTimeString(date("Y-m-d " . $time[1] . ":00"));
            $now = Carbon::now();
            if($init->lt($now) && $end->gt($now)){
                return true;
            }
        }
        return false;
    }
    public static function DeliveryActive(){
        return SiteConfig::where([
            ['key_config', 'delivery_orders_active'],
            ['value', '1'],
        ])->count() > 0;
    }
    public static function CurentlyAccepting(){
        return self::IsJobTime() && self::DeliveryActive();
    }
    private static function addLeadingZeros($number, $n) {
        $numberStr = (string) $number;
        $numberLength = strlen($numberStr);
        
        if ($numberLength >= $n) {
            return $numberStr;
        } else {
            $numberOfZeros = $n - $numberLength;
            $zeros = str_repeat('0', $numberOfZeros);
            return $zeros . $numberStr;
        }
    }
    public static function renderAdminOrder($order){
        
        $order->products = array_map(function($pr){
            $product = $pr->pivot;
            $product->name = $pr->name;
            $product->unit_price = floatval($product->unit_price);
            $product->total_sell = floatval($product->total_sell);
            $product->total_item = floatval($product->total_item);
            $product->total_discount = floatval($product->total_discount);
            $product->total_additionals = floatval($product->total_additionals);
            $product->additionals = json_decode($product->additionals);
            return $product;
        }, $order->products);
        $order->locator = 'WA-' . self::addLeadingZeros($order->id, 8);
        return $order;
    }
}

?>