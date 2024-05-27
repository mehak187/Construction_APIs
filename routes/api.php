<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\HRController;

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
    Route::post('register/worker', 'registerWorker');
    Route::post('register/merchant', 'registerMerchant');
    Route::post('login', 'login');
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(WorkerController::class)->group(function(){
        Route::post('leave', 'leave');
        Route::get('myLeaves', 'myLeaves');
        Route::get('test', 'test');
        Route::post('checkin', 'checkin');
    });
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(HRController::class)->group(function(){
        Route::get('allLeaves', 'allLeaves');
        Route::post('updateLeaveStatus', 'updateLeaveStatus');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
