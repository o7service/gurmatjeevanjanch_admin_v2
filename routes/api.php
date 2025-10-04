<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("customer/register", [UserController::class, 'register']);
Route::post("customer/login", [UserController::class, 'login']);


Route::post("brand/add", [BrandController::class, 'store'])->middleware('auth:api');
Route::get("brand/all", [BrandController::class, 'index']);
Route::get("brand/single/{id}", [BrandController::class, 'show']);
Route::get("brand/delete/{id}", [BrandController::class, 'destroy']);
Route::post("brand/update/{id}", [BrandController::class, 'update']);

// products Api .......................................................
Route::get("product/all", [ProductController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
