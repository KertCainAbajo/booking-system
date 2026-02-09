<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\BookingForm;
use App\Livewire\Customer\BookingTracker;
use App\Livewire\Customer\ServiceHistory;
use App\Livewire\Customer\Profile as CustomerProfile;
use App\Livewire\Staff\Dashboard as StaffDashboard;
use App\Livewire\Staff\BookingCalendar;
use App\Livewire\Staff\BookingDetail;
use App\Livewire\Staff\InvoiceGenerator;
use App\Livewire\Staff\Profile as StaffProfile;
use App\Livewire\Staff\BookingManagement as StaffBookingManagement;
use App\Livewire\Staff\BookingHistory;
use App\Livewire\BusinessOwner\Dashboard as OwnerDashboard;
use App\Livewire\BusinessOwner\RevenueReport;
use App\Livewire\BusinessOwner\Profile as OwnerProfile;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\ServiceManagement;
use App\Livewire\Admin\SystemMonitoring;
use App\Livewire\Admin\BookingManagement;
use App\Livewire\Admin\BookingDetail as AdminBookingDetail;
use App\Livewire\Admin\Profile as AdminProfile;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Guest\GuestBookingForm;
use App\Livewire\Guest\BookingConfirmation;
use App\Livewire\Guest\BookingTracker as GuestBookingTracker;

// Public Guest Routes
Route::get('/', function () {
    return view('guest-landing');
})->name('guest.home');

Route::get('/contact', function () {
    return view('guest-contact');
})->name('guest.contact');

Route::get('/book', GuestBookingForm::class)->name('guest.booking');
Route::get('/booking/confirmation/{reference}', BookingConfirmation::class)->name('guest.booking.confirmation');
Route::get('/booking/track', GuestBookingTracker::class)->name('guest.booking.track');

// Redirect authenticated users to their dashboard
// Note: Only staff, business_owner, and it_admin have access
Route::get('/dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $roleName = $user->role->name;
        
        $dashboardRoute = match($roleName) {
            'it_admin' => 'admin.dashboard',
            'business_owner' => 'owner.dashboard',
            'staff' => 'staff.dashboard',
            default => 'staff.dashboard',
        };
        
        return redirect()->route($dashboardRoute);
    }
    
    return redirect()->route('staff.login');
})->name('dashboard');

// Staff Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', StaffDashboard::class)->name('dashboard');
    Route::get('/calendar', BookingCalendar::class)->name('calendar');
    Route::get('/bookings', StaffBookingManagement::class)->name('bookings');
    Route::get('/history', BookingHistory::class)->name('history');
    Route::get('/booking/{id}', BookingDetail::class)->name('booking.detail');
    Route::get('/invoice/{id}', InvoiceGenerator::class)->name('invoice');
    Route::get('/profile', StaffProfile::class)->name('profile');
});

// Business Owner Routes
Route::middleware(['auth', 'role:business_owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', OwnerDashboard::class)->name('dashboard');
    Route::get('/reports', RevenueReport::class)->name('reports');
    Route::get('/profile', OwnerProfile::class)->name('profile');
});

// IT Admin Routes
Route::middleware(['auth', 'role:it_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('users');
    Route::get('/services', ServiceManagement::class)->name('services');
    Route::get('/monitoring', SystemMonitoring::class)->name('monitoring');
    Route::get('/bookings', BookingManagement::class)->name('bookings');
    Route::get('/booking/{id}', AdminBookingDetail::class)->name('booking.detail');
    Route::get('/profile', AdminProfile::class)->name('profile');
});

require __DIR__.'/auth.php';
