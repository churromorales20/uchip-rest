<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\GoogleMapsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminMenuController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/products/home', [ProductsController::class, 'ProductsHome']);
Route::get('/addresses/autocomplete', [GoogleMapsController::class, 'AddressAutocomplete']);
Route::get('/addresses/place/info', [GoogleMapsController::class, 'PlaceInformation']);
Route::get('/orders/config', [OrdersController::class, 'GetConfiguration']);
Route::post('/order/create', [OrdersController::class, 'Create']);
Route::post('/order/check', [OrdersController::class, 'PreCheck']);
Route::get('/order/coupon/check', [OrdersController::class, 'CouponCheck']);
Route::get('/convertcsv', [OrdersController::class, 'convertCSVFile']);
Route::get('/test', [OrdersController::class, 'TEST']);
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::get('/auth/seed', [AuthController::class, 'createRoles']);
Route::get('/auth/test', [AuthController::class, 'test']);
Route::get('/admin/menu/hello', [AdminMenuController::class, 'getMenuInformation']);
Route::group([
    'middleware' => 'auth:sanctum'
], function() {
    Route::get('/admin/menu', [AdminMenuController::class, 'getMenuInformation']);
    Route::get('/admin/menu/additionals', [AdminMenuController::class, 'getAdditionals']);
    Route::get('/auth/user', [AuthController::class, 'userCheck']);

    Route::post('/admin/menu/categories/create', [AdminMenuController::class, 'createCategory']);
    Route::post('/admin/menu/categories/changestatus', [AdminMenuController::class, 'changeCategoryStatus']);
    Route::post('/admin/menu/products/additionals', [AdminMenuController::class, 'changeCategoryStatus']);
    Route::post('/admin/menu/products/additionals/association', [AdminMenuController::class, 'changeProductAssociation']);
    Route::post('/admin/menu/products/additionals/order', [AdminMenuController::class, 'changeProductAdditionalsOrder']);
    Route::post('/admin/menu/products/price/update', [ProductsController::class, 'changeProductPrice']);
    Route::post('/admin/menu/products/info/update', [ProductsController::class, 'changeProductInfo']);
    Route::post('/admin/menu/products/status/update', [ProductsController::class, 'changeProductStatus']);
    Route::post('/admin/menu/products/duplicate', [ProductsController::class, 'duplicate']);
    Route::post('/admin/menu/products/create', [ProductsController::class, 'create']);
    Route::post('/admin/menu/products/delete', [ProductsController::class, 'delete']);
    Route::post('/admin/menu/products/image/update', [ProductsController::class, 'imageUpdate']);
    Route::post('/admin/menu/categories/products/update-order', [ProductsController::class, 'reorderInCategory']);
    Route::post('/admin/menu/categories/update-order', [AdminMenuController::class, 'changeCategoryStatus']);
    Route::post('/admin/menu/additionals/options/add', [AdminMenuController::class, 'additionalAddOption']);
    Route::post('/admin/menu/additionals/options/update', [AdminMenuController::class, 'additionalUpdateOption']);
    Route::post('/admin/menu/additionals/options/delete', [AdminMenuController::class, 'additionalDeleteOption']);
    Route::post('/admin/menu/additionals/options/order', [AdminMenuController::class, 'additionalOptionsReorder']);
    //Route::post('/admin/menu/additionals/delete', [AdminMenuController::class, 'additionalUpdateOption']);
    Route::post('/admin/menu/additionals/update/quantity', [AdminMenuController::class, 'additionalUpdateQty']);
    Route::post('/admin/menu/additionals/update/behavior', [AdminMenuController::class, 'additionalUpdateBehavior']);
    Route::post('/admin/menu/additionals/update/name', [AdminMenuController::class, 'additionalUpdateName']);
    Route::get('/admin/menu/additionals/create', [AdminMenuController::class, 'additionalAutoCreate']);
    Route::post('/admin/menu/additionals/delete', [AdminMenuController::class, 'additionalDelete']);
    Route::post('/admin/menu/additionals/duplicate', [AdminMenuController::class, 'additionalDuplicate']);
});