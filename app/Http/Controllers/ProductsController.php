<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
class ProductsController extends Controller
{
    public function __construct(){
        
    }
    public function ProductsHome(Request $request){
        //sleep(4);
        return response()->json(['status'=>'success', 'home'=> Category::with(['products.additionals' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->get()]);
    }
}
