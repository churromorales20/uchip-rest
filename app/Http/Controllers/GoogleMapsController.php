<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ixudra\Curl\Facades\Curl;
class GoogleMapsController extends Controller
{
    private $google_api = "https://maps.googleapis.com/maps/api/";
    private $google_api_key;
    public function __construct(){
        $this->$google_api_key = env('GOOGLE_API_KEY');
    }
    private function generateSession(){
        return bin2hex(random_bytes(8));
    }
    public function AddressAutocomplete(Request $request){
        //TODO: ADD LARAVEL CAHCE 5 MINS
        $session_id = !empty($request->input('session_id')) ? $request->input('session_id') : $this->generateSession();
        //dd($session_id);
        $response = Curl::to($this->google_api . 'place/autocomplete/json')
                    ->withData([
                        'input' => $request->input('place_term'),
                        'key' => $this->google_api_key,
                        'sessiontoken' => $session_id
                    ])
                    ->asJson()
                    ->get();
        return response()
                ->json([
                    'status'=>'success', 
                    'places'=> $response,
                    'session_id' => $session_id
                ]);
    }
    public function PlaceInformation(Request $request){
        //TODO: ADD LARAVEL CAHCE 5 MINS
        $session_id = !empty($request->input('session_id')) ? $request->input('session_id') : '';
        //dd($session_id);
        $response = Curl::to($this->google_api . 'place/details/json')
                    ->withData([
                        'place_id' => $request->input('place_id'),
                        'key' => $this->google_api_key,
                        'sessiontoken' => $session_id
                    ])
                    ->asJson()
                    ->get();
        return response()
                ->json([
                    'status'=>'success', 
                    'info'=> $response->result
                ]);
    }
}
