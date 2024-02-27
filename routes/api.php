<?php

use App\Http\Controllers\Api\rolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('roles', [rolesController::class, 'index']);
Route::post('roles', [rolesController::class, 'create']);
Route::get('roles/{id}', [rolesController::class, 'show']);
Route::put('roles/{id}/edit', [rolesController::class, 'update']);
Route::delete('roles', [rolesController::class, 'delete']);



