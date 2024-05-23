<?php

// use cors;
use Illuminate\Http\Request;
use App\Http\Middleware\Cors;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateApi;
use App\Http\Controllers\Api\imageUpload;
use App\Http\Controllers\Api\subContacts;
use App\Http\Controllers\Api\AddSecondary;
use App\Http\Controllers\Api\petController;
use App\Http\Controllers\Api\ResetPassword;
use App\Http\Controllers\Api\Authcontroller;
use App\Http\Controllers\Api\adminController;
use App\Http\Controllers\Api\rolesController;
use App\Http\Controllers\Api\bannerController;
use App\Http\Controllers\Api\AllPageController;
use App\Http\Controllers\Api\RegCodeController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\CodeTypescontroller;
use App\Http\Controllers\Api\subscriberController;
use App\Http\Controllers\Api\CountryCodeController;
use App\Http\Controllers\Api\RequestChatController;
use App\Http\Controllers\Api\RequestSentController;
use App\Http\Controllers\Api\SubsCirclesController;
use App\Http\Controllers\Api\defalutStatusController;
use App\Http\Controllers\Api\appDevicehangleController;
use App\Http\Controllers\Api\subscriberLoginController;
use App\Http\Controllers\Api\subscribersKidsController;
use App\Http\Controllers\Api\SubsCirclesMemberController;
use App\Http\Controllers\Api\subscriberMailOtpVerification;
use App\Http\Controllers\Api\SubsChildPermissionsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Login Token generation API
Route::post('login', [Authcontroller::class, 'login']);

// Route to send OTP for password reset
Route::post('password/reset/send-link', [ResetPassword::class, 'sendResetLinkEmail']);
Route::get('/reset-password', [ResetPassword::class, 'showResetPasswordForm'])->name('reset.password.show');
Route::put('/reset-password', [ResetPassword::class, 'updatePassword'])->name('reset.password.update');

// Route to reset password using OTP
// Route::post('password/reset', [ResetPassword::class, 'reset']);

// For Roles
Route::get('roles', [rolesController::class, 'index']);
Route::get('roles/{id}', [rolesController::class, 'show']);
Route::post('roles', [rolesController::class, 'create']);
Route::put('roles/{id}/edit', [rolesController::class, 'update']);
Route::delete('roles/{id}', [rolesController::class, 'delete']);

// FOr Subscribers

//** For Admin side */
Route::get('subscriber', [subscriberController::class, 'index']);// For Admin
//** */

// Route::post('subscriber', [subscriberController::class, 'create']);// Not _using _now
Route::post('subscriberloginsData', [subscriberLoginController::class, 'create']);
Route::post('subscriberloginsCreateAccount/{id}', [subscriberLoginController::class, 'createAccounts']);
Route::put('/subscribers/{id}', [subscriberLoginController::class, 'update']);

//For Devices catch
Route::get('appDevices', [appDevicehangleController::class, 'show']);
Route::post('appDevices', [appDevicehangleController::class, 'DeviceValidate']);


//** For Entry Code Type */
// ** Fetching the Device ID's  **//
Route::get('codetype', [CodeTypescontroller::class, 'index']);
// ** Fetching the Device ID's  **//


// For Reg_code Data
Route::get('regcodedata/{id}', [RegCodeController::class, 'index']);
Route::get('regcodedata/{id}/userid/{user_id}', [RegCodeController::class, 'show']);
Route::post('verify', [RegCodeController::class, 'verify']);
Route::get('verify/{entryId}/{sub_login}', [RegCodeController::class, 'verifyAndCreate'])->name('verifyAndCreate');;


//secondary person CRUD
Route::get('addsecondary', [AddSecondary::class, 'index']);
Route::post('addsecondary/{primaryId}', [AddSecondary::class, 'addSecondary']);


// Subscriber Authentication API
Route::middleware([AuthenticateApi::class])->group(function () {
    // Subscribers  ( Create, View, Update, Delete)
    Route::get('subscriberlogins', [SubscriberLoginController::class, 'index']);
    Route::get('maincreatedAccounts/{subscriberId?}', [subscriberLoginController::class, 'maincreatedaccount']);
    Route::get('subscriberlogins/{id}', [subscriberLoginController::class, 'show']);
    Route::put('subscriberlogins/{id}/edit', [subscriberLoginController::class, 'update']);
    Route::delete('subscriberlogins/{id}', [subscriberLoginController::class, 'delete']);
    Route::get('mainSecondary/{id?}', [subscriberLoginController::class, 'mainSecondary']);

    // Subscriberskids  ( Create, View, Update, Delete)
    Route::get('subscribersKids/{id?}', [subscribersKidsController::class, 'index']);
    Route::post('subscribersKids', [subscribersKidsController::class, 'create']);
    Route::get('subscriberkidsdata/{subscriberId}', [subscribersKidsController::class, 'getKidsBySubscriberId']);
    Route::get('subscriberpetdata/{subscriberId}', [petController::class, 'getKidsBySubscriberId']);


    // subscribers Contacts with Kids
    Route::get('subcontacts/{id?}', [subContacts::class, 'index']);
    Route::get('subcontacts/subscriberId/{subscriberId}/{id?}', [subContacts::class, 'getSubContactedData']);
    Route::get('subcontacts/contactedId/{contactedId}/{id?}', [subContacts::class, 'getContactedData']);
    Route::post('subcontacts', [subContacts::class, 'store']);

});

// Image uplaod testing
Route::post('imageupload', [imageUpload::class, 'create']);

// For Subs Circles
Route::post('subcircles', [SubsCirclesController::class, 'index']);

// For Subs Circles Members
Route::post('submembers', [SubsCirclesMemberController::class, 'index']);

// For Subs Circles Permission
Route::post('subpermission', [SubsChildPermissionsController::class, 'index']);

// For Request Sent To
Route::get('requestsent', [RequestSentController::class, 'index']);

Route::post('requestsent',[RequestSentController::class, 'store']);

// For Request
Route::get('requests', [RequestController::class, 'index']);
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

// countries
Route::get('country-code', [CountryCodeController::class, 'index']);


