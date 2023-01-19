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
}

?>