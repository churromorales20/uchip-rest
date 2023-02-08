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
Route::group([
    'middleware' => 'auth:sanctum'
], function() {
    Route::get('/admin/menu', [AdminMenuController::class, 'getMenuInformation']);
    Route::post('/admin/menu/categories/create', [AdminMenuController::class, 'createCategory']);
    Route::post('/admin/menu/categories/changestatus', [AdminMenuController::class, 'changeCategoryStatus']);
    Route::post('/admin/menu/categories/update-order', [AdminMenuController::class, 'changeCategoryStatus']);
    Route::get('/auth/user', [AuthController::class, 'userCheck']);
});