<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => ['is_login']], function(){

    Route::get('/register', [UserController::class, 'loadRegister'])->name('loadRegister');
    Route::post('/user-registered', [UserController::class, 'registered'])->name('registered');

    Route::get('/referral-register', [UserController::class, 'loadReferralRegister']);
    Route::get('/email-verification/{token}', [UserController::class, 'emailVerifications']);

    Route::get('/login', [UserController::class, 'loadLogin']);
    Route::post('/login', [UserController::class, 'userLogin'])->name('login');

});

Route::group(['middleware' => ['is_logout']], function(){

    Route::get('/dashboard', [UserController::class, 'loadDashboard'])->name('dashboard');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/referral-track', [UserController::class, 'referralTrack'])->name('referralTrack');
    Route::get('/delete-account', [UserController::class, 'deleteAccount'])->name('deleteAccount');

});
