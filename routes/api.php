<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\GoogleMapsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Api\AuthController;
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