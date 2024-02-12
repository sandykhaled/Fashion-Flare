<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[LoginController::class,'login']);
Route::post('register',[RegisterController::class,'register']);
Route::middleware('auth:api')->group(function(){
    Route::get('profile/show',[ProfileController::class,'show']);
    Route::post('logout',[ProfileController::class,'logout']);
    Route::post('profile/update',[ProfileController::class,'update']);
    Route::get('products',function (){
        return 'products';
    });
});
