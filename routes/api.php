<?php

use App\Http\Controllers\Api\adminController;
use App\Http\Controllers\Api\defalutStatusController;
use App\Http\Controllers\API\SubsCirclesController;
use App\Http\Controllers\Api\subscriberLoginController;
use App\Http\Controllers\Api\subscriberMailOtpVerification;
use App\Http\Controllers\Api\subscribersKidsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\rolesController;
use App\Http\Controllers\Api\subscriberController;
use App\Http\Controllers\Api\AllPageController;
use App\Http\Controllers\Api\SubsCirclesMemberController;
use App\Http\Controllers\Api\SubsChildPermissionsController;
use App\Http\Controllers\Api\RequestSentController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\RequestChatController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// For Roles
Route::get('roles', [rolesController::class, 'index']);
Route::get('roles/{id}', [rolesController::class, 'show']);
Route::post('roles', [rolesController::class, 'create']);
Route::put('roles/{id}/edit', [rolesController::class, 'update']);
Route::delete('roles/{id}', [rolesController::class, 'delete']);


// FOr Subscribers
Route::get('subscriber', [subscriberController::class, 'index']);
Route::post('subscriber', [subscriberController::class, 'create']);

Route::middleware('auth:api')->group(function () {
    // Subscribers Logins ( Create, View, Update, Delete)
    Route::get('subscriberlogins', [subscriberLoginController::class, 'index']);
    Route::post('subscriberlogins', [subscriberLoginController::class, 'create']);
    Route::get('subscriberloginsCreateAccount', [subscriberLoginController::class, 'showcreateAccounts']);
    Route::post('subscriberloginsCreateAccount/{id}', [subscriberLoginController::class, 'createAccounts']);
    Route::get('subscriberlogins/{id}', [subscriberLoginController::class, 'show']);
    Route::put('subscriberlogins/{id}/edit', [subscriberLoginController::class, 'update']);
    Route::delete('subscriberlogins/{id}', [subscriberLoginController::class, 'delete']);
    Route::get('/subscriberlogins/{subscriberId}/family-members', [subscriberLoginController::class, 'showcreateAccount']);

    // Subscribers Logins ( Create, View, Update, Delete)
    Route::get('subscribersKids', [subscribersKidsController::class, 'index']);
    Route::post('subscribersKids', [subscribersKidsController::class, 'create']);

    // For Subs Circles
    Route::post('subcircles', [SubsCirclesController::class, 'index']);

    // For Subs Circles Members
    Route::post('submembers', [SubsCirclesMemberController::class, 'index']);

    // For Subs Circles Permission
    Route::post('subpermission', [SubsChildPermissionsController::class, 'index']);

    // For Request Sent To
    Route::post('requestsent', [RequestSentController::class, 'index']);

    // For Request
    Route::post('request', [RequestController::class, 'index']);

    // For Request Chat
    Route::post('requestchat', [RequestChatController::class, 'index']);

    // For Status
    Route::get('defaultStatus', [defalutStatusController::class, 'index']);
    Route::post('defaultStatus', [defalutStatusController::class, 'create']);
    Route::get('defaultStatus/{id}', [defalutStatusController::class, 'show']);
    Route::put('defaultStatus/{id}/edit', [defalutStatusController::class, 'update']);


    // For Admin users
    Route::get('adminUsers', [adminController::class, 'showAdminUsers']);
    Route::get('adminUsers/{id}', [adminController::class, 'show']);

    // For Admin about
    Route::get('allPages', [AllPageController::class, 'index']);
    Route::post('allPages', [AllPageController::class, 'store']);
    Route::get('allPages/{id}', [AllPageController::class, 'show']);
    Route::put('allPages/{id}/edit', [AllPageController::class, 'update']);
    Route::delete('allPages/{id}', [AllPageController::class, 'destroy']);

    // Mail Verification
    Route::post('otpVerification', [subscriberMailOtpVerification::class, 'getInfoFromApp']);
});







