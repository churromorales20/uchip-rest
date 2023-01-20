<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\GoogleMapsController;
use App\Http\Controllers\OrdersController;
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

Route::get('/products/home', [App\Http\Controllers\ProductsController::class, 'ProductsHome']);
Route::get('/addresses/autocomplete', [App\Http\Controllers\GoogleMapsController::class, 'AddressAutocomplete']);
Route::get('/addresses/place/info', [App\Http\Controllers\GoogleMapsController::class, 'PlaceInformation']);
Route::get('/orders/config', [App\Http\Controllers\OrdersController::class, 'GetConfiguration']);
Route::post('/order/create', [App\Http\Controllers\OrdersController::class, 'Create']);
Route::post('/order/check', [App\Http\Controllers\OrdersController::class, 'PreCheck']);
Route::get('/order/coupon/check', [App\Http\Controllers\OrdersController::class, 'CouponCheck']);