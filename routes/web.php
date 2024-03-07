<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminuserRegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;



// authentication & Security
// Route::get('/', function () {
//     return view('security/login');
// });
// Authentication Routes...
Route::get('/', [adminuserRegistrationController::class, 'showLoginForm'])->name('login'); // Define the controller method for showing the login form
Route::post('/', [adminuserRegistrationController::class, 'authenticate'])->name('authenticate');

Route::get('/forgotPassword', function () {
    return view('security/forgotPassword');
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Route to handle the forgot password form submission
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// change password route


Route::get('/password/change', [ForgotPasswordController::class,'showChangeForm'])->name('password.change');
Route::post('/password/update',[ForgotPasswordController::class,'updatePassword'])->name('password.update');
Route::get('/changePassword', function () {
    return view('security/changePassword');
});

// registration
Route::get('/registration', [adminuserRegistrationController::class, 'index']);
Route::post('/registration', [adminuserRegistrationController::class, 'view']);
Route::get('/export-registrations', [adminuserRegistrationController::class, 'export']);



// Admin pannel
Route::get('/dashboard', function () {
    return view('dashboard');
});

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

