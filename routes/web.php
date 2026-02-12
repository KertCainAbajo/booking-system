<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\BookingForm;
use App\Livewire\Customer\BookingTracker;
use App\Livewire\Customer\ServiceHistory;
use App\Livewire\Customer\Profile as CustomerProfile;
use App\Livewire\Customer\TwoFactorSetup;
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

// Redirect root to login page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('guest.home');

// Public Guest Routes
Route::get('/contact', function () {
    return view('guest-contact');
})->name('guest.contact');

Route::get('/book', GuestBookingForm::class)->name('guest.booking');
Route::get('/booking/confirmation/{reference}', BookingConfirmation::class)->name('guest.booking.confirmation');
Route::get('/booking/track', GuestBookingTracker::class)->name('guest.booking.track');

// Redirect authenticated users to their dashboard
// Note: Supports staff, business_owner, it_admin, and customer accounts
Route::get('/dashboard', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Load necessary relationships
        $user->load(['customer', 'role']);
        
        // Check role name first for explicit routing
        if ($user->role && $user->role->name === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        
        // Also check customer relationship as fallback
        if ($user->customer) {
            return redirect()->route('customer.dashboard');
        }
        
        // Get role name with fallback
        $roleName = $user->role?->name ?? 'staff';
        
        $dashboardRoute = match($roleName) {
            'it_admin' => 'admin.dashboard',
            'business_owner' => 'owner.dashboard',
            'staff' => 'staff.dashboard',
            default => 'staff.dashboard',
        };
        
        return redirect()->route($dashboardRoute);
    }
    
    return redirect()->route('login');
})->name('dashboard');

// Customer Routes (for registered customers with accounts)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', CustomerDashboard::class)->name('dashboard');
    Route::get('/book', BookingForm::class)->name('book');
    Route::get('/tracker', BookingTracker::class)->name('tracker');
    Route::get('/history', ServiceHistory::class)->name('history');
    Route::get('/profile', CustomerProfile::class)->name('profile');
    Route::get('/2fa/setup', TwoFactorSetup::class)->name('2fa.setup');
});

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
