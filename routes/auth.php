<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

// Unified Login Route - All user types use the same login page
Route::middleware('guest')->group(function () {
    // Main login route for all user types (customers, staff, admin, business owner)
    Volt::route('login', 'pages.auth.login')
        ->name('login');

    // Redirect old staff and customer login routes to unified login
    Route::get('staff/login', function () {
        return redirect()->route('login');
    })->name('staff.login');

    Route::get('customer/login', function () {
        return redirect()->route('login');
    })->name('customer.login');

    // Customer registration enabled
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    // Staff registration disabled - staff must be created by admin
    Route::get('staff/register', function () {
        abort(404);
    })->name('staff.register');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    // Google OAuth Initiate Route
    Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('auth.google');
});

// Google OAuth Callback Route - Must be outside guest middleware
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
    
    Route::match(['get', 'post'], 'logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        // All users logout to the same unified login page
        return redirect()->route('login');
    })->name('logout');
});
