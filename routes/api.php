<?php

use App\Http\Controllers\Products\BrandsController;
use App\Http\Controllers\Products\CategoriesController;
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
        Route::post('/forgot-password', 'sendResetPasswordEmail');
        Route::post('/reset-password', 'resetPassword');

        Route::middleware('auth.user')->group(function () {
            Route::get('/me', 'getUser');
            Route::post('/logout', 'logoutAsUser');
        });
    });


    //Admin routes
    Route::prefix('/admin')->group(function (){
        Route::post('/login', [AuthController::class, 'loginAsAdmin']);
        Route::middleware('auth.user')->group(function () {
            Route::get('/me', [AuthController::class, 'getUser']);
            Route::post('/logout', [AuthController::class, 'logoutAsAdmin']);
            Route::get('/categories', [CategoriesController::class, 'getCategories']);
            Route::controller(CategoriesController::class)->prefix('/category')->group(function () {
                Route::post('/create', 'createCategory');
                Route::get('/{uuid}', 'getCategory');
                Route::put('/{uuid}', 'updateCategory');
                Route::patch('/{uuid}', 'updateCategory');
                Route::delete('/{uuid}', 'deleteCategory');
            });
            Route::get('/brands', [BrandsController::class, 'getBrands']);
            Route::controller(BrandsController::class)->prefix('/brand')->group(function () {
                Route::post('/create', 'createBrand');
                Route::get('/{uuid}', 'getBrand');
                Route::put('/{uuid}', 'updateBrand');
                Route::patch('/{uuid}', 'updateBrand');
                Route::delete('/{uuid}', 'deleteBrand');
            });
        });
    });

});
