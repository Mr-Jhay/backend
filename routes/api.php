<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register',[AuthController::class,'register']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    "middleware" => "auth:sanctum"
], function(){
    Route::get('userprofile',[AuthController::class,'userprofile']);
    Route::get('/users/{id}', [AuthController::class, 'show']);
    Route::get('logout',[AuthController::class,'logout']);
});