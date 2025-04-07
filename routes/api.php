<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('product')->group(function () {
    Route::get('product-list', [ProductController::class, 'index']);
    Route::post('product-create', [ProductController::class, 'store']);
    Route::get('product-show/{id}', [ProductController::class, 'show']);
    Route::put('product-update/{id}', [ProductController::class, 'update']);
    Route::delete('product-delete/{id}', [ProductController::class, 'destroy']);
});