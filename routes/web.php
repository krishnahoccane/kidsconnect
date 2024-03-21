<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AllPageController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\web\urlRestrictionController;
use App\Http\Controllers\adminuserRegistrationController;
use App\Http\Controllers\web\subscriberUserProfileConstroller;



// URL Authentication Routes...
// Route::middleware(['auth.custom'])->group(function () {
//     Route::get('/dashboard', [adminuserRegistrationController::class, 'dashboard']) ->name('dashboard');
//     // Other dashboard routes...
// });

// Admin Registration - Export to Excel Controllers
Route::get('/registration', [adminuserRegistrationController::class, 'index']);
Route::post('/registration', [adminuserRegistrationController::class, 'view']);
Route::get('/export-registrations', [adminuserRegistrationController::class, 'export']);


// Admin Login Logout Controllers
Route::get('/', [adminuserRegistrationController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/', [adminuserRegistrationController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [adminuserRegistrationController::class, 'logout'])->name('logout');

// Admin Password access - with mail Controllers
Route::get('/forgotPassword', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgotPassword', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Auth Roots - Direct access - URL Restrictions
Route::middleware(['auth'])->group(function () {
    // Authenticate routes
    //Admin access cotrollers
    Route::get('/password/change', [ForgotPasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/update', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
    Route::get('/dashboard', [adminuserRegistrationController::class, 'dashboard'])->name('dashboard');
    Route::get('/adminProfile', [urlRestrictionController::class, 'adminProfile'])->name('adminProfile');
    Route::get('/adminUsers', [urlRestrictionController::class, 'adminUsers'])->name('adminUsers');

    //Admin Subscriber - access cotrollers
    Route::get('/appUsers', [urlRestrictionController::class, 'appUsers'])->name('appUsers');
    Route::get('/createdUsers', [urlRestrictionController::class, 'createdUsers'])->name('createdUsers');
    Route::get('/feedback', [urlRestrictionController::class, 'feedback'])->name('feedback');
    Route::get('/userProfile', [urlRestrictionController::class, 'userProfile'])->name('userProfile');

    //Admin Pages - access cotrollers
    Route::get('/about', [urlRestrictionController::class, 'about'])->name('about');
    Route::get('/termsAndconditions', [urlRestrictionController::class, 'termsAndconditions'])->name('termsAndconditions');
    Route::get('/privacyPolicy', [urlRestrictionController::class, 'privacyPolicy'])->name('privacyPolicy');
    Route::get('/faq', [urlRestrictionController::class, 'faq'])->name('faq');
    Route::get('/totalRegistrations', [urlRestrictionController::class, 'totalRegistrations'])->name('totalRegistrations');
    Route::get('/totalCalendarschedules', [urlRestrictionController::class, 'totalCalendarschedules'])->name('totalCalendarschedules');

    //Admin Subscriber Feedback - access cotrollers
    Route::get('/appFeedbacks', [urlRestrictionController::class, 'appFeedbacks'])->name('appFeedbacks');

    // Admin Subscriber - Approval Access API
    Route::get('userProfile/{id}', [subscriberUserProfileConstroller::class, 'show']);
    Route::get('userProfile/{id}/approve', [subscriberUserProfileConstroller::class, 'approve']);
    Route::get('userProfile/{id}/deny', [subscriberUserProfileConstroller::class, 'deny']);
    Route::delete('userProfile/{id}', [subscriberUserProfileConstroller::class, 'delete']);

    // Admin Pages - Access API
    Route::get('allPages', [AllPageController::class, 'index']);
    Route::post('allPages', [AllPageController::class, 'store']);
    Route::get('allPages/{id}', [AllPageController::class, 'show']);
    Route::put('allPages/{id}/edit', [AllPageController::class, 'update']);
    Route::delete('allPages/{id}', [AllPageController::class, 'destroy']);

});







