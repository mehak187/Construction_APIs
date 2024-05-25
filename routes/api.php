<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;

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

Route::controller(AuthController::class)->group(function(){
    Route::post('register/user', 'registerUser');
    Route::post('register/merchant', 'registerMerchant');
    Route::post('login', 'login');

    Route::get('/login/facebook', 'redirectToFacebook');
    Route::post('/login/facebook/callback', 'handleFacebookCallback');
    Route::get('/login/google', 'redirectToGoogle');
    Route::post('/login/google/callback', 'handleGoogleCallback');
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::controller(ProductController::class)->middleware(['auth:sanctum'])->group(function(){
    Route::post('store/product', 'storeProduct');
    Route::post('update/product', 'updateProduct');
    Route::post('delete/product/{id}', 'deleteProduct');
    Route::get('products', 'products');
    Route::get('single/product/{id}', 'singleProduct');
    Route::post('vote/product', 'voteProduct');

    Route::post('add/promotion', 'addPromotion');
    Route::get('get/promotion', 'getPromotion');
});
Route::controller(MerchantController::class)->middleware(['auth:sanctum'])->group(function(){
    Route::post('store/merchant', 'storeMerchant');
});

Route::controller(PaymentController::class)->middleware(['auth:sanctum'])->group(function(){
    Route::post('/payment', 'processPayment');
    Route::get('/purchased/history', 'purchasedHistory');
    Route::get('/single/purchased/{id}', 'singlePurchased');
});

Route::controller(ProfileController::class)->middleware(['auth:sanctum'])->group(function(){
    Route::get('/getProfile', 'getProfile');
    Route::post('/updateProfile', 'updateProfile');
    Route::post('/changeProfile', 'changeProfile');
    Route::get('/getData', 'getData');

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotification']);
    Route::get('/markNotificationAsRead', [NotificationController::class, 'markNotificationAsRead']);
});