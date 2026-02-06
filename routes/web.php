<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Customer\BookingForm;
use App\Livewire\Customer\BookingTracker;
use App\Livewire\Customer\ServiceHistory;
use App\Livewire\Staff\BookingCalendar;
use App\Livewire\Staff\BookingDetail;
use App\Livewire\Staff\InvoiceGenerator;
use App\Livewire\BusinessOwner\Dashboard as OwnerDashboard;
use App\Livewire\BusinessOwner\RevenueReport;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\ServiceManagement;
use App\Livewire\Admin\SystemMonitoring;

Route::view('/', 'welcome');

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');
    Route::get('/booking', BookingForm::class)->name('booking');
    Route::get('/tracker', BookingTracker::class)->name('tracker');
    Route::get('/history', ServiceHistory::class)->name('history');
});

// Staff Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', BookingCalendar::class)->name('dashboard');
    Route::get('/booking/{id}', BookingDetail::class)->name('booking.detail');
    Route::get('/invoice/{id}', InvoiceGenerator::class)->name('invoice');
});

// Business Owner Routes
Route::middleware(['auth', 'role:business_owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', OwnerDashboard::class)->name('dashboard');
    Route::get('/reports', RevenueReport::class)->name('reports');
});

// IT Admin Routes
Route::middleware(['auth', 'role:it_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/users', UserManagement::class)->name('users');
    Route::get('/services', ServiceManagement::class)->name('services');
    Route::get('/monitoring', SystemMonitoring::class)->name('monitoring');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
