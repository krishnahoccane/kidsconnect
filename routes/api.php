<?php

// use cors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateApi;
use App\Http\Middleware\Cors;
use App\Http\Controllers\Api\Authcontroller;
use App\Http\Controllers\Api\adminController;
use App\Http\Controllers\Api\rolesController;
use App\Http\Controllers\Api\bannerController;
use App\Http\Controllers\Api\AllPageController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\subscriberController;
use App\Http\Controllers\Api\RequestChatController;
use App\Http\Controllers\Api\RequestSentController;
use App\Http\Controllers\Api\SubsCirclesController;
use App\Http\Controllers\Api\defalutStatusController;
use App\Http\Controllers\Api\subscriberLoginController;
use App\Http\Controllers\Api\subscribersKidsController;
use App\Http\Controllers\Api\SubsCirclesMemberController;
use App\Http\Controllers\Api\subscriberMailOtpVerification;
use App\Http\Controllers\Api\SubsChildPermissionsController;
use App\Http\Controllers\Api\petController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Login Token generation API
Route::post('login', [Authcontroller::class, 'login']);


// For Roles
Route::get('roles', [rolesController::class, 'index']);
Route::get('roles/{id}', [rolesController::class, 'show']);
Route::post('roles', [rolesController::class, 'create']);
Route::put('roles/{id}/edit', [rolesController::class, 'update']);
Route::delete('roles/{id}', [rolesController::class, 'delete']);

// FOr Subscribers
Route::get('subscriber', [subscriberController::class, 'index']);
Route::post('subscriber', [subscriberController::class, 'create']);


// Subscriber Authentication API
Route::middleware([AuthenticateApi::class])->group(function () {
    Route::get('subscriberlogins', [SubscriberLoginController::class, 'index']);
});



// Subscribers  ( Create, View, Update, Delete)
Route::post('subscriberlogins', [subscriberLoginController::class, 'create']);
Route::get('maincreatedAccounts/{subscriberId?}', [subscriberLoginController::class, 'maincreatedaccount']);
Route::post('subscriberloginsCreateAccount/{id}', [subscriberLoginController::class, 'createAccounts']);
Route::get('subscriberlogins/{id}', [subscriberLoginController::class, 'show']);
Route::put('subscriberlogins/{id}/edit', [subscriberLoginController::class, 'update']);
Route::delete('subscriberlogins/{id}', [subscriberLoginController::class, 'delete']);
Route::get('mainSecondary/{id?}',[subscriberLoginController::class, 'mainSecondary']);
// Route::get('/subscriberlogins/{subscriberId}/family-members', [subscriberLoginController::class, 'showcreateAccount']);

// Subscriberskids  ( Create, View, Update, Delete)
Route::get('subscribersKids', [subscribersKidsController::class, 'index']);
Route::get('subscribersKids/{id}', [subscribersKidsController::class, 'show']);
Route::get('subscriberkidsdata/{subscriberId}',[subscribersKidsController::class,'getKidsBySubscriberId']);

Route::get('subscriberpetdata/{subscriberId}',[petController::class,'getKidsBySubscriberId']);


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
Route::get('request', [RequestController::class, 'index']);
Route::post('/requests', [RequestController::class, 'create']);
Route::get('/requests/{id}', [RequestController::class, 'show']);
Route::put('/requests/{id}', [RequestController::class, 'update']);


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
Route::post('forgotPassword', [SubscriberMailOtpVerification::class, 'forgotpassword']);

// Banner Upload
Route::get('allBanners', [bannerController::class, 'index']);
Route::post('allBanners', [bannerController::class, 'store']);
Route::get('allBanners/{id}', [bannerController::class, 'show']);
Route::put('allBanners/{id}/edit', [bannerController::class, 'update']);
Route::delete('allBanners/{id}', [bannerController::class, 'destroy']);
Route::delete('allBanners', [bannerController::class, 'destroyall']);



