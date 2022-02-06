<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('v1/logout', [AuthController::class, 'logout']);

    Route::apiResource('v1/schools', SchoolController::class)->whereUuid('school');
    Route::apiResource('v1/schools/{school}/users', UserController::class)->scoped()->whereUuid(['school', 'user']);
    Route::apiResource('v1/schools/{school}/groups', GroupController::class)->scoped()->whereUuid(['school', 'group']);
});
