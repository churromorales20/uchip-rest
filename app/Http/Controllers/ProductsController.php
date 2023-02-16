<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use DB;
use App\Models\ProductsAdditionals;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
class ProductsController extends Controller
{
    public function __construct(){
        
    }
    public function ProductsHome(Request $request){
        //sleep(4);
        return response()->json(['status'=>'success', 'home'=> Category::with(['products.additionals.items_data' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->get()]);
    }
    public function changeProductPrice(Request $request){
        if($product = Product::withTrashed()->where('id', $request->input('product_id'))->first()){
            $price = $request->input('price');
            if($request->input('type') == 'price'){
                $product->price = is_null($price) ? 0 : $price;
            }else{
                $product->price_discount = $request->input('price');
            }
            $product->save();
            return response()->json([
                'status' => 'success',
            ]);
        }
    }
    public function changeProductInfo(Request $request){
        if($product = Product::withTrashed()->where('id', $request->input('product_id'))->first()){
            $new_val = $request->input('new_val');
            $new_val = is_null($new_val) ? '' : $new_val;
            if($request->input('type') == 'description'){
                $product->description = $new_val;
            }else{
                $product->name = $new_val;
            }
            $product->save();
            return response()->json([
                'status' => 'success',
            ]);
        }
    }
    public function changeProductStatus(Request $request){
        if($product = Product::withTrashed()->where('id', $request->input('product_id'))->first()){
            $new_val = $request->input('new_val');
            if($new_val === true){
                $product->restore();
            }else{
                $product->delete();
            }
            return response()->json([
                'status' => 'success',
            ]);
        }
    }
    public function create(Request $request){
        $category_id = $request->input('category_id');
        $new_order = Product::withTrashed()->where('category_id', $category_id)->max('order') + 1;
        $product = Product::create([
            'name' => 'Nuevo item',
            'category_id' => $category_id,
            'image' => '',
            'price' => 0,
            'order' => $new_order,
            'description' => '',
        ]);
        $product->load('additionals');
        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }
    public function delete(Request $request){
        if($product = Product::where('id', $request->input('id'))->withTrashed()->first()){
            $product->forceDelete();
            return response()->json([
                'status' => 'success',
            ]);
        }
    }
    public function imageServe(Request $request, $image_name){
        $cache_key = $image_name . '_product_image_';
        $image = Cache::rememberForever($cache_key, function () use ($image_name) {
            if (Storage::disk('public')->exists("images/{$image_name}")) {
                return Storage::disk('public')->get("images/{$image_name}");
            } 
            abort(404);
        });
        $response = Response::make($image, 200);
        $response->header('Content-Type', 'image/png');
        return $response;
    }
    public function imageUpdate(Request $request){
        $product_id = $request->input('product_id');
        if($product = Product::where('id', $product_id)->withTrashed()->first()){
            $base64_image = $request->input('base64_image');
            $image_name = DB::transaction(function() use ($product, $base64_image){
                $image_name = bin2hex(random_bytes(12));
                if (Storage::disk('public')->exists("images/{$product->image}")) {
                    Storage::disk('public')->delete("images/{$product->image}");
                }
                $product->image = $image_name . '.uchip';
                $product->save();
                Storage::disk('public')->putFileAs('images', $base64_image, $image_name);
                return $image_name . '.uchip';
            });
            
            return response()->json([
                'status' => 'success',
                'category_id' => $product->category_id,
                'product_id' => $product->id,
                'image_name' => $image_name
            ]);
        }
    }
    public function reorderInCategory(Request $request){
        //$category_id = $request->input('category_id');
        $new_order = $request->input('new_order_map');
        DB::transaction(function() use ($new_order){
            foreach ($new_order as $order_item) {
                Product::where('id', $order_item['id'])->update(['order' => $order_item['order']]);
            }
        });
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function duplicate(Request $request){
        $category_id = $request->input('category_id');
        $product_id = $request->input('product_id');
        if($product_to_copy = Product::withTrashed()->with('additionals')->where('id', $product_id)->first()){
            $new_product = DB::transaction(function() use ($category_id, $product_to_copy){
                $new_order = Product::withTrashed()->where('category_id', $category_id)->max('order') + 1;
                $new_product = Product::create([
                    'name' => $product_to_copy->name . ' (copia)',
                    'category_id' => $category_id,
                    'image' => $product_to_copy->image,
                    'price' => $product_to_copy->price,
                    'price_discount' => $product_to_copy->price_discount,
                    'order' => $new_order,
                    'description' => $product_to_copy->description,
                    'deleted_at' => $product_to_copy->deleted_at,
                ]);
                foreach ($product_to_copy->additionals as $additional) {
                    //AdditionalOption::where('id', $option['id'])->update(['order' => $option['order']]);
                    /*$new_product->additionals()->create([
                        'additional_id' => $additional->id,
                        'order' => $additional->pivot->order,
                    ]);*/
                    ProductsAdditionals::create([
                        'product_id' => $new_product->id,
                        'additional_id' => $additional->id,
                        'order' => $additional->pivot->order,
                    ]);
                }
                $new_product->load('additionals');
                return $new_product;
            });
            return response()->json([
                'status' => 'success',
                'product' => $new_product
            ]);
        }
    }
    
}
