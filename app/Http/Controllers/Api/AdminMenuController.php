<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
class AdminMenuController extends Controller
{
    public function createCategory(Request $request){
        //sleep(2);
        return response()->json([
            'status' => 'success',
            'category' => [
                'created_at' => null,
                'deleted_at' => null,
                'description' => "",
                'id' => 10,
                'name' => "Nueva categoria",
                'order' => 7,
                'products' => [],
                'updated_at' => null,
            ]
        ], 200);
    }
    public function changeCategoryStatus(Request $request){
        sleep(6);
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function getMenuInformation(Request $request){
        //sleep(8);
        return response()->json([
            'status'=>'success', 
            'home'=> Category::with(['products' => function ($query) {
                        $query->withTrashed()->with('additionals');
                    }])
                    ->withTrashed()->get()
        ]);
    }
}