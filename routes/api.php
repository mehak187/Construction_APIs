<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\HRController;
use App\Http\Controllers\Api\SuperviserController;
use App\Http\Controllers\Api\CommonController;


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
    Route::post('login', 'login');
    Route::post('sendResetLinkEmail', 'sendResetLinkEmail');
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(WorkerController::class)->group(function(){
        Route::get('test', 'test');
        Route::post('checkin', 'checkin');
        Route::post('checkout', 'checkout');
        Route::get('myAttendance', 'myAttendance');
        Route::get('myprojects', 'myprojects');
        Route::post('todayAttendance', 'todayAttendance');
        Route::get('mySalary', 'mySalary');
        // -------office-worker-----
        Route::post('leave', 'leave');
        Route::get('myLeaves', 'myLeaves');
        Route::post('updateLeave', 'updateLeave');
        // -------office-worker and admin-----
        Route::get('allProjects', 'allProjects');
        Route::post('saveProjects', 'saveProjects');
        Route::post('addSalary', 'addSalary');
        Route::get('allSalary', 'allSalary');
        Route::post('updateProject', 'updateProject');
        Route::get('allAttendance', 'allAttendance');
        Route::get('empProfile', 'empProfile');
        Route::post('register/worker', 'registerWorker');
        Route::post('updateWorker', 'updateWorker');
    });
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(CommonController::class)->group(function(){
        Route::get('myprofile', 'myprofile');
    });
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(HRController::class)->group(function(){
        Route::get('allLeaves', 'allLeaves');
        Route::post('updateLeaveStatus', 'updateLeaveStatus');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(SuperviserController::class)->group(function(){
        Route::get('myWorkersAttendance', 'myWorkersAttendance');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
