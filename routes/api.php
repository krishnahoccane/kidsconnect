<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\rolesController;
use App\Http\Controllers\Api\subscribersController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('roles', [rolesController::class, 'index']);
Route::get('subscriber', [subscribersController::class, 'index']);

Route::post('roles', [rolesController::class, 'create']);
Route::post('subscriber', [subscribersController::class, 'create']);
Route::get('roles/{id}', [rolesController::class, 'show']);
Route::put('roles/{id}/edit', [rolesController::class, 'update']);
Route::delete('roles', [rolesController::class, 'delete']);



