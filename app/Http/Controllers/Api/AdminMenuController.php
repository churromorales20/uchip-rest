<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\Additional;
use App\Models\AdditionalOption;
use App\Models\ProductsAdditionals;
use DB;
class AdminMenuController extends Controller
{
    public function createCategory(Request $request){
        $new_order = Category::withTrashed()->max('order') + 1;
        $category = Category::create([
            'description' => "",
            'name' => "Nueva categoria",
            'order' => $new_order,
        ]);
        $category->products = [];
        //$category->deleted_at = false;
        return response()->json([
            'status' => 'success',
            'category' => Category::with(['products' => function ($query) {
                        $query->withTrashed()->with(['additionals' => function($query){
                            $query->with('items_data')->withTrashed();
                        }]);
                    }])
                    ->where('id', $category->id)
                    ->first()
        ], 200);
    }
    public function changeCategoryStatus(Request $request){
        $category_id = $request->input('category_id');
        if($category = Category::where('id', $category_id)->withTrashed()->first()){
            $new_status = $request->input('new_status');
            if($new_status === true){
                $category->restore();
            }else{
                $category->delete();
            }
            return response()->json([
                'status' => 'success',
            ]);   
        }
    }
    public function updateCategoryName(Request $request){
        $category_id = $request->input('category_id');
        if($category = Category::where('id', $category_id)->withTrashed()->first()){
            $category->name = $request->input('new_name');
            $category->save();
            return response()->json([
                'status' => 'success',
            ]);   
        }
    }
    public function changeCategoryOrder(Request $request){
        $new_order_map = $request->input('new_order_map');
        DB::transaction(function() use ($new_order_map){
            foreach ($new_order_map as $option) {
                Category::where('id', $option['id'])->withTrashed()->update(['order' => $option['order']]);
            }
        });
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function deleteCategory(Request $request){
        $category_id = $request->input('category_id');
        if($category = Category::where('id', $category_id)->withTrashed()->first()){
            $category->forceDelete();
            return response()->json([
                'status' => 'success',
            ]);
        }
        abort(404);
    }
    public function additionalDeleteOption(Request $request){
        if($option = AdditionalOption::where('id', $request->input('id'))->withTrashed()->first()){
            $option->forceDelete();
            return response()->json([
                'status' => 'success',
            ]);
        }
        abort(404);
    }
    public function additionalUpdateQty(Request $request){
        $newval = $request->input('value');
        Additional::where('id', $request->input('id'))
                    ->update($request->input('type') == 'max' ? [
                        'max_items' => $newval
                    ] : [
                        'min_items' => $newval
                    ]);
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function changeProductAdditionalsOrder(Request $request){
        $product_id = $request->input('product_id');
        $new_order = $request->input('new_order');
        DB::transaction(function() use ($product_id, $new_order){
            foreach ($new_order as $order_item) {
                ProductsAdditionals::where([
                    ['product_id', $product_id],
                    ['additional_id', $order_item['id']]
                ])->update(['order' => $order_item['order']]);
            }
        });
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function changeProductAssociation(Request $request){
        $id_category = $request->input('category_id');
        $product_id = $request->input('product_id');
        if($request->input('type') == 'add'){
            $new_order = ProductsAdditionals::where('additional_id', $id_category)->max('order') + 1;
            ProductsAdditionals::create([
                'product_id' => $product_id,
                'additional_id' => $id_category,
                'order' => $new_order,
            ]);
        }else{
            DB::delete('DELETE FROM products_additionals WHERE product_id = ? AND additional_id = ?', [$product_id, $id_category]);
        }
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function additionalDuplicate(Request $request){
        $add = Additional::where('id', $request->input('id'))->with(['items_data' => function($query){
                        $query->withTrashed();
                }])->first();
        $new_cat = DB::transaction(function() use ($add){
            $new_add = Additional::create([
                'name' => $add->name . ' (copia)',
                'single' => $add->single === true  ? '1' : '0',
                'required' => $add->required === true  ? '1' : '0',
                'max_items' => $add->max_items,
                'min_items' => $add->min_items,
            ]);
            foreach ($add->items_data as $option) {
                //AdditionalOption::where('id', $option['id'])->update(['order' => $option['order']]);
                AdditionalOption::create([
                    'additional_category' => $new_add->id,
                    'name' => $option->name,
                    'price' => $option->price,
                    'max' => $option->max,
                    'order' => $option->order,
                    'deleted_at' => $option->active === true ? null : date('Y-m-d'),
                ]);
            }
            return Additional::where('id', $new_add->id)->with(['items_data' => function($query){
                        $query->withTrashed();
                    }])->first();
        });
        return response()->json([
            'status' => 'success',
            'category' => $new_cat
        ]);
    }
    public function additionalDelete(Request $request){
        //$newval = $request->input('value');
        DB::delete('DELETE FROM additionals WHERE id = ?', [$request->input('id')]);
        //Additional::destroy($request->input('id'));
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function additionalAutoCreate(Request $request){
        
        $additional = Additional::create([
            'name' => 'Nueva categoria',
            'single' => '1',
            'required' => '0',
            'max_items' => 1,
            'min_items' => 0,
        ]);
        $additional->items_data = [];
        return response()->json([
            'status' => 'success',
            'category' => Additional::where('id', $additional->id)
                                    ->with('products')
                                    ->with('items_data')
                                    ->first()
        ]);
    }
    public function additionalUpdateBehavior(Request $request){
        $newval = $request->input('value');
        Additional::where('id', $request->input('id'))
                    ->update($request->input('type') == 'req' ? [
                        'required' =>  strval($newval)
                    ] : [
                        'single' =>  strval($newval)
                    ]);
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function additionalUpdateName(Request $request){
        $newval = $request->input('value');
        Additional::where('id', $request->input('id'))
                    ->update([
                        'name' =>  strval($newval)
                    ]);
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function additionalOptionsReorder(Request $request){
        $options_order = $request->input('options_order');
        //dd($options_order);
        /*return response()->json([
            'status' => 'success',
            'sss' => $options_order
        ]);*/
        DB::transaction(function() use ($options_order){
            foreach ($options_order as $option) {
                AdditionalOption::where('id', $option['id'])->update(['order' => $option['order']]);
            }
        });
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function additionalAddOption(Request $request){
        $id_category = $request->input('category_id');
        $new_order = AdditionalOption::where('additional_category', $id_category)->withTrashed()->max('order') + 1;
        $option = AdditionalOption::create([
            'additional_category' => $id_category,
            'name' => 'Nueva opción',
            'price' => 0,
            'max' => 1,
            'order' => $new_order,
        ]);
        return response()->json([
            'status'=>'success', 
            'option'=> $option
        ]);
    }
    public function additionalUpdateOption(Request $request){
        $option_id = $request->input('option_id');
        $type = $request->input('type');
        $new_val = $request->input('new_val');
        if($option = AdditionalOption::withTrashed()->where('id',$option_id)->first()) {
            if($type != 'status'){
                switch ($type) {
                    case 'name':
                        $option->name = $new_val;
                        break;
                    case 'price':
                        $option->price = $new_val;
                        break;
                    case 'max':
                        $option->max = $new_val;
                        break;
                }
                $option->save();
            }elseif($new_val === true){
                $option->restore();
            }else{
                $option->delete();
            }
            return response()->json([
                'status'=>'success'
            ]);
        }
        
    }
    public function getMenuInformation(Request $request){
        //sleep(8);
        return response()->json([
            'status'=>'success', 
            'home'=> Category::with(['products' => function ($query) {
                        $query->withTrashed()->with(['additionals' => function($query){
                            $query->with('items_data')->withTrashed();
                        }]);
                    }])
                    ->withTrashed()->get()
        ]);
    }
    public function getAdditionals(Request $request){
        //sleep(8);
        return response()->json([
            'status'=>'success', 
            'additionals'=> Additional::with(['products' => function ($query) {
                        $query->withTrashed()->select('id')->orderBy('name');
                    }])->with(['items_data' => function($query){
                        $query->withTrashed();
                    }])->orderBy('name')->get(),
            //'products' => Product::select(['name','id'])->withTrashed()->orderBy('name')->get()
            'products' => Product::select(['products.name','products.id','products.order'])
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->orderBy('categories.order', 'asc')
                            ->orderBy('products.order', 'asc')
                            ->withTrashed()
                            ->orderBy('name')
                            ->get()
        ]);
    }
}