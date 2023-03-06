<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteConfig;
use App\Http\Controllers\Controller;
class ConfigurationsController extends Controller
{
    public function GetConfiguration(Request $request){
        //Cache::forget('_website_initial_configurations_');
        $configs = Cache::remember('_website_initial_configurations_', 10, function () use ($request){
            $configs = [];
            foreach(SiteConfig::all()->toArray() as $config){
                $configs[$config['key_config']] = $config['value'];
            }
            return $configs;
        });

        return response()->json([
            'status'=>'success', 
            'config'=> $configs
        ]);
    }
}
