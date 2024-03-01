<?php

use App\Http\Controllers\Api\adminController;
use App\Http\Controllers\Api\defalutStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\rolesController;
use App\Http\Controllers\Api\subscribersController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// For Roles
Route::get('roles', [rolesController::class, 'index']);
Route::get('roles/{id}', [rolesController::class, 'show']);
Route::post('roles', [rolesController::class, 'create']);
Route::put('roles/{id}/edit', [rolesController::class, 'update']);


// FOr Subscribers
Route::get('subscriber', [subscribersController::class, 'index']);
Route::post('subscriber', [subscribersController::class, 'create']);
Route::delete('roles', [rolesController::class, 'delete']);

// For Status
Route::get('defaultStatus', [defalutStatusController::class, 'index']);
Route::post('defaultStatus', [defalutStatusController::class, 'create']);
Route::get('defaultStatus/{id}', [defalutStatusController::class, 'show']);
Route::put('defaultStatus/{id}/edit', [defalutStatusController::class, 'update']);


// For Admin users
Route::get('adminUsers',[adminController::class,'showAdminUsers']);
Route::get('adminUsers/{id}',[adminController::class,'show']);





