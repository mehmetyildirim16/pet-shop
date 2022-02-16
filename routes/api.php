<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

Route::prefix('/v1')->group(function () {
    //Dummy route to check if the API is working
    Route::get('/ping', fn()=> ['status' => 'OK']);

    //User routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'loginAsUser');
        Route::post('/logout', 'logoutAsUser');

        Route::middleware('auth.user')->group(function () {
            Route::get('/me', 'getUser');
        });
    });


    //Admin routes
    Route::prefix('/admin')->group(function (){
        Route::post('/login', [AuthController::class, 'loginAsAdmin']);
        Route::post('/logout', [AuthController::class, 'logoutAsAdmin']);
        Route::middleware('auth.user')->group(function () {
            Route::get('/me', [AuthController::class, 'getUser']);
        });
    });

});
