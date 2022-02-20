<?php

use App\Http\Controllers\Orders\OrdersController;
use App\Http\Controllers\Orders\OrderStatusController;
use App\Http\Controllers\Orders\PaymentController;
use App\Http\Controllers\Products\BrandsController;
use App\Http\Controllers\Products\CategoriesController;
use App\Http\Controllers\Products\ProductsController;
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

            Route::get('/payments', [PaymentController::class, 'getPayments']);
            Route::controller(PaymentController::class)->prefix('/payment')->group(function () {
                Route::post('/create', 'createPayment');
                Route::get('/{uuid}', 'getPayment');
                Route::put('/{uuid}', 'updatePayment');
                Route::patch('/{uuid}', 'updatePayment');
                Route::delete('/{uuid}', 'deletePayment');
            });

            Route::get('/orders', [OrdersController::class, 'getOrders']);
            Route::controller(OrdersController::class)->prefix('/order')->group(function () {
                Route::get('/{uuid}', 'getOrder');
                Route::post('/create', 'createOrder');
                Route::put('/{uuid}', 'updateOrder');
                Route::patch('/{uuid}', 'updateOrder');
                Route::delete('/{uuid}', 'deleteOrder');
            });
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
            Route::get('/products', [ProductsController::class, 'getProducts']);
            Route::controller(ProductsController::class)->prefix('/product')->group(function () {
                Route::post('/create', 'createProduct');
                Route::get('/{uuid}', 'getProduct');
                Route::put('/{uuid}', 'updateProduct');
                Route::patch('/{uuid}', 'updateProduct');
                Route::delete('/{uuid}', 'deleteProduct');
            });

            Route::get('/order-statuses', [OrderStatusController::class, 'getOrderStatuses']);
            Route::controller(OrderStatusController::class)->prefix('/order-status')->group(function () {
                Route::post('/create', 'createOrderStatus');
                Route::get('/{uuid}', 'getOrderStatus');
                Route::put('/{uuid}', 'updateOrderStatus');
                Route::patch('/{uuid}', 'updateOrderStatus');
                Route::delete('/{uuid}', 'deleteOrderStatus');
            });
        });
    });

});
