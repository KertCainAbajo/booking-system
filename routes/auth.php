<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

// Staff, Business Owner, and Admin Login Routes
// Customer login is separate with 2FA support
Route::middleware('guest')->group(function () {
    // Staff/Admin login (single authentication system for all internal users)
    Volt::route('staff/login', 'pages.auth.login')
        ->name('staff.login');

    // Customer login with Google Authenticator 2FA
    Volt::route('customer/login', 'pages.auth.customer-login')
        ->name('customer.login');

    // Keep old login route for backwards compatibility, redirect to staff login
    Route::get('login', function () {
        return redirect()->route('staff.login');
    })->name('login');

    // Registration disabled - users must be created by admin
    Route::get('register', function () {
        abort(404);
    })->name('register');

    Route::get('staff/register', function () {
        abort(404);
    })->name('staff.register');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
    
    Route::match(['get', 'post'], 'logout', function () {
        $user = Auth::user();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        // Redirect based on user type
        if ($user && $user->customer) {
            return redirect()->route('customer.login');
        }
        
        return redirect()->route('staff.login');
    })->name('logout');
});
