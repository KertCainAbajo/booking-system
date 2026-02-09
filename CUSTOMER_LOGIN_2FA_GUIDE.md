# Customer Login with Google Authenticator (2FA)

## Overview
A complete customer login system with Google Authenticator two-factor authentication has been implemented, matching the design of the staff login page.

## What Has Been Implemented

### 1. **Database & Models**
- ✅ Added 2FA fields to users table:
  - `google2fa_secret` - Stores the encrypted secret key
  - `google2fa_enabled` - Boolean flag for 2FA status
  - `recovery_codes` - JSON array of backup codes

### 2. **Customer Login Page** 
- **Route**: `/customer/login`
- **Features**:
  - Same elegant design as staff login
  - Email and password authentication
  - Two-factor authentication with Google Authenticator
  - Recovery code support
  - Password visibility toggle
  - Remember me functionality
  - Loading states and animations

### 3. **2FA Setup Page**
- **Route**: `/customer/2fa/setup`
- **Features**:
  - Step-by-step setup guide
  - QR code generation for easy app pairing
  - Manual secret key entry option
  - Code verification before enabling
  - 8 recovery codes generation
  - Print/save recovery codes functionality

### 4. **Packages Installed**
- `pragmarx/google2fa-laravel` - Google 2FA implementation
- `simplesoftwareio/simple-qrcode` - QR code generation

## How to Use

### For Customers (First Time Setup)

1. **Login to Account**
   - Visit: `http://your-domain/customer/login`
   - Enter email and password
   - Click "Log In"

2. **Enable 2FA (Optional but Recommended)**
   - Visit: `http://your-domain/customer/2fa/setup`
   - Follow the 3-step process:
     - Download Google Authenticator app (iOS/Android)
     - Scan the QR code or enter secret key manually
     - Verify with 6-digit code from the app
   - **IMPORTANT**: Save your recovery codes!

3. **Future Logins with 2FA Enabled**
   - Enter email and password as usual
   - System will prompt for 6-digit code
   - Open Google Authenticator app
   - Enter the current code
   - Click "Verify & Log In"

### For Administrators

**Creating Customer Accounts:**
1. Create a user account via admin panel
2. Create a customer record linked to that user
3. Customer can then login at `/customer/login`

**Example using Tinker:**
```php
// Create user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'role_id' => 1, // Or appropriate role
]);

// Create customer
$customer = Customer::create([
    'user_id' => $user->id,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '1234567890',
    'is_guest' => false,
]);
```

## Routes Added

### Authentication Routes (`routes/auth.php`)
```php
// Customer login
Route::get('customer/login', 'pages.auth.customer-login')
    ->name('customer.login');

// Logout (updated to handle customer redirects)
Route::post('logout', ...)->name('logout');
```

### Customer Dashboard Routes (`routes/web.php`)
```php
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', CustomerDashboard::class);
    Route::get('/book', BookingForm::class);
    Route::get('/tracker', BookingTracker::class);
    Route::get('/history', ServiceHistory::class);
    Route::get('/profile', CustomerProfile::class);
    Route::get('/2fa/setup', TwoFactorSetup::class);
});
```

## Files Created/Modified

### New Files
1. `app/Livewire/Forms/CustomerLoginForm.php` - Login form with 2FA logic
2. `app/Livewire/Customer/TwoFactorSetup.php` - 2FA setup component
3. `resources/views/livewire/pages/auth/customer-login.blade.php` - Login view
4. `resources/views/livewire/customer/two-factor-setup.blade.php` - 2FA setup view
5. `database/migrations/2026_02_09_160040_add_google2fa_fields_to_users_table.php`

### Modified Files
1. `app/Models/User.php` - Added 2FA fields and casts
2. `routes/auth.php` - Added customer login route
3. `routes/web.php` - Added customer dashboard routes
4. `composer.json` - Added 2FA packages

## Security Features

- ✅ Rate limiting (5 attempts per minute)
- ✅ Account lockout on failed attempts
- ✅ Encrypted 2FA secret storage
- ✅ 8 recovery codes for device loss
- ✅ Recovery codes are single-use
- ✅ Session regeneration on login
- ✅ CSRF protection
- ✅ Password hashing

## Testing the Implementation

### Test Customer Login
1. Visit: `http://localhost:8000/customer/login`
2. Enter valid customer credentials
3. Should redirect to customer dashboard

### Test 2FA Setup
1. Login as customer
2. Visit: `http://localhost:8000/customer/2fa/setup`
3. Scan QR code with Google Authenticator
4. Verify with generated code
5. Save recovery codes

### Test 2FA Login
1. Logout
2. Login again with same customer account
3. After password, should see 2FA prompt
4. Enter 6-digit code from app
5. Should login successfully

### Test Recovery Code
1. Logout
2. Login with password
3. Enter one of your recovery codes instead of 2FA code
4. Should login successfully
5. That recovery code is now invalid (one-time use)

## Design Consistency

The customer login page maintains the same design elements as the staff login:
- ✅ Same logo and branding
- ✅ Consistent input styling with icons
- ✅ Same auth-input-wrapper classes
- ✅ Password visibility toggle
- ✅ Animated loading states
- ✅ Responsive design (mobile-friendly)
- ✅ Smooth transitions and hover effects

## Next Steps (Optional Enhancements)

- [ ] Add email verification for new customer accounts
- [ ] Add "Trusted Devices" feature to skip 2FA for 30 days
- [ ] Add SMS backup authentication option
- [ ] Add 2FA enforcement policy (make it mandatory for all customers)
- [ ] Add audit log for 2FA events
- [ ] Add ability to disable 2FA from profile
- [ ] Add ability to regenerate recovery codes

## Troubleshooting

**Q: QR Code not showing?**
- Ensure `simplesoftwareio/simple-qrcode` is installed
- Clear browser cache
- Check Laravel logs for errors

**Q: 2FA code not working?**
- Ensure phone's time is synced (2FA codes are time-based)
- Check if you're copying the full 6-digit code
- Try using a recovery code instead

**Q: Lost recovery codes?**
- Admin needs to manually disable 2FA in database
- User can then re-enable 2FA at `/customer/2fa/setup`

**Q: Customer can't login?**
- Verify user has a linked customer record
- Check that `user.customer` relationship exists
- Check database for `google2fa_enabled` field

## Support

For issues or questions, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console for JavaScript errors
3. Database migrations ran successfully
4. All packages installed correctly

---

**Implementation Date**: February 9, 2026  
**Laravel Version**: 12.0  
**PHP Version**: 8.2+
