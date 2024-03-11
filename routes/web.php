<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\PreventBackHistory;
// use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\AllPageController;
use App\Http\Controllers\ForgotPasswordController;
// use App\Http\Controllers\Api\subscriberUserProfileConstroller;

// use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\adminuserRegistrationController;
use App\Http\Controllers\web\subscriberUserProfileConstroller;



// URL Authentication Routes...
// Route::middleware(['auth.custom'])->group(function () {
//     Route::get('/dashboard', [adminuserRegistrationController::class, 'dashboard']) ->name('dashboard');
//     // Other dashboard routes...
// });

// Admin Routes
Route::get('/registration', [adminuserRegistrationController::class, 'index']);
Route::post('/registration', [adminuserRegistrationController::class, 'view']);
Route::get('/export-registrations', [adminuserRegistrationController::class, 'export']);

// Route::get('/login', function () {
//     return view('security.login');
// })->name('login');Route::get('/', [adminuserRegistrationController::class, 'showLoginForm']); // Define the controller method for showing the login form
Route::get('/', [adminuserRegistrationController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/', [adminuserRegistrationController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [adminuserRegistrationController::class, 'logout'])->name('logout');

// Forgot Routes..
Route::get('/forgotPassword', [ForgotPasswordController::class,'showLinkRequestForm'])->name('password.request');
Route::post('/forgotPassword', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');

// change password route

Route::middleware(['auth'])->group(function () {
    // Define routes that need to prevent back history here
    Route::get('/password/change', [ForgotPasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/update', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
    Route::get('/dashboard', [adminuserRegistrationController::class, 'dashboard']) ->name('dashboard');
});

// subscriber-userprofiles Handling
Route::get('userProfile/{id}', [subscriberUserProfileConstroller::class, 'show']);
Route::put('userProfile/{id}/edit', [subscriberUserProfileConstroller::class, 'update']);
Route::delete('userProfile/{id}', [subscriberUserProfileConstroller::class, 'delete']);

// Pages
Route::get('allPages',[AllPageController:: class,'index']);
Route::post('allPages',[AllPageController:: class,'store']);
Route::get('allPages/{id}',[AllPageController:: class,'show']);
Route::put('allPages/{id}/edit', [AllPageController::class, 'update']);
Route::delete('allPages/{id}', [AllPageController::class, 'destroy']);

// Admin pannel
// Route::get('/dashboard', function () {
//     return view('dashboard');
// });

// User management
Route::get('/adminUsers', function () {
    return view('users/adminUsers');
});
Route::get('/appUsers', function () {
    return view('users/appUsers');
});
Route::get('/feedback', function () {
    return view('users/feedback');
});
Route::get('/userProfile', function () {
    return view('users/userProfile');
});
Route::get('/adminProfile', function () {
    return view('users/adminProfile');
});

// Page management
Route::get('/about', function () {
    return view('pages/about');
});
Route::get('/termsAndconditions', function () {
    return view('pages/termsAndconditions');
});
Route::get('/privacyPolicy', function () {
    return view('pages/privacyPolicy');
});
Route::get('/faq', function () {
    return view('pages/faq');
});

// Admin Reports
Route::get('/totalRegistrations', function () {
    return view('reports/totalRegistrations');
});
Route::get('/totalCalendarschedules', function () {
    return view('reports/totalCalendarschedules');
});
Route::get('/appFeedbacks', function () {
    return view('reports/appFeedbacks');
});



