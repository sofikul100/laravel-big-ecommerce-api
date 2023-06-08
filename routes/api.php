<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------

|
*/

//=========================Authentication routes for admin=============//
Route::group(['prefix'=>'admin','as'=>'admin'], function(){
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);

    Route::middleware('auth:admin_api')->group(function () {
        Route::post('logout', [AuthController::class,'logout']);
        Route::get('me', [AuthController::class,'me']);
    });
});






//=================== all categories routes here============//
Route::group(['prefix'=>'categorie','as'=>'categorie'], function(){
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('create',[CategorieController::class,'create']);
    });

    Route::middleware('auth:customer_api')->group(function () {
       
    });

    //==========public routes=========//
    Route::get('categories',[CategorieController::class,'index']);
});









//==================== authentication routes for customers================//
Route::group(['prefix'=>'customer','as'=>'customer'],function (){
 
    Route::post('register', [CustomerAuthController::class,'register']);
    Route::post('login', [CustomerAuthController::class,'login']);
    Route::middleware('auth:customer_api')->group(function () {

        Route::get('me', [CustomerAuthController::class,'me']);
        Route::post('logout', [CustomerAuthController::class,'logout']);
        
    });
});
