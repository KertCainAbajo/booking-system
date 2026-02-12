# Google OAuth Customer Login - Database Storage Guide

## 🎯 Overview

This guide explains **step-by-step** how customer data is stored in the database when logging in with Google OAuth.

---

## 📊 Database Tables Involved

### **1. users table**
Stores the main user account information:
```sql
- id (primary key)
- name
- email (unique)
- google_id (unique, nullable) ← Stores Google user ID
- password (hashed, auto-generated for Google users)
- role_id (foreign key to roles table)
- email_verified_at (auto-set for Google users)
- created_at
- updated_at
```

### **2. customers table**
Stores customer-specific profile information:
```sql
- id (primary key)
- user_id (foreign key to users table) ← Links to user account
- name
- email
- phone (nullable)
- is_guest (boolean, 0 for Google OAuth users)
- address (nullable)
- created_at
- updated_at
```

### **3. model_has_roles table** (Spatie Permissions)
Links users to roles:
```sql
- role_id (foreign key to roles table)
- model_type (typically 'App\Models\User')
- model_id (user's id)
```

---

## 🔄 Step-by-Step Flow

### **Step 1: Customer Clicks "Sign in with Google"**

**Location:** 
- Login page: `resources/views/livewire/pages/auth/login.blade.php`
- Register page: `resources/views/livewire/pages/auth/register.blade.php`

**Action:**
```html
<a href="{{ route('auth.google') }}">
    <svg>...</svg> Sign in with Google
</a>
```

**Route Triggered:**
```php
GET /auth/google → GoogleAuthController@redirectToGoogle
```

**What Happens:**
- User is redirected to Google's OAuth consent page
- Google shows: "booking-system wants to access your: Name, Email"

---

### **Step 2: Google Authorization**

**User Actions:**
1. Selects Google account
2. Reviews permissions
3. Clicks "Allow"

**Google Redirects Back:**
```
GET /auth/google/callback?code=AUTHORIZATION_CODE
```

---

### **Step 3: Callback Processing**

**Route:** `auth.google.callback`  
**Controller:** `GoogleAuthController@handleGoogleCallback`  
**File:** `app/Http/Controllers/Auth/GoogleAuthController.php`

#### **3A. Get User Info from Google**

**Code (Line 32-37):**
```php
$googleUser = Socialite::driver('google')->user();

// Received data:
$googleUser->getName()    // "John Doe"
$googleUser->getEmail()   // "john@gmail.com"
$googleUser->getId()      // "1234567890" ← Google ID
```

#### **3B. Check if User Exists**

**Code (Line 44-46):**
```php
$user = User::with(['customer', 'role'])
    ->where('email', $googleUser->getEmail())
    ->orWhere('google_id', $googleUser->getId())
    ->first();
```

**Database Query:**
```sql
SELECT users.*, customers.*, roles.*
FROM users
LEFT JOIN customers ON users.id = customers.user_id
LEFT JOIN roles ON users.role_id = roles.id
WHERE users.email = 'john@gmail.com' 
   OR users.google_id = '1234567890';
```

---

### **SCENARIO A: New Customer (First Time)**

#### **Step 4A: Get Customer Role**

**Code (Line 118-122):**
```php
$customerRole = Role::where('name', 'customer')->first();
```

**Database Query:**
```sql
SELECT * FROM roles WHERE name = 'customer';
-- Returns: id = 3, name = 'customer'
```

#### **Step 5A: Create User Account**

**Code (Line 125-134):**
```php
$user = User::create([
    'name' => $googleUser->getName(),
    'email' => $googleUser->getEmail(),
    'password' => Hash::make(Str::random(32)),
    'email_verified_at' => now(),
    'role_id' => $customerRole->id,
    'google_id' => $googleUser->getId(),
]);
```

**Database Insert:**
```sql
INSERT INTO users (
    name, 
    email, 
    password, 
    email_verified_at, 
    role_id, 
    google_id, 
    created_at, 
    updated_at
) VALUES (
    'John Doe',
    'john@gmail.com',
    '$2y$12$randomHashedPassword',
    '2026-02-12 10:30:00',
    3,
    '1234567890',
    '2026-02-12 10:30:00',
    '2026-02-12 10:30:00'
);
-- Returns: id = 100
```

#### **Step 6A: Assign Spatie Role**

**Code (Line 137):**
```php
$user->assignRole('customer');
```

**Database Insert:**
```sql
INSERT INTO model_has_roles (role_id, model_type, model_id)
VALUES (3, 'App\\Models\\User', 100);
```

#### **Step 7A: Create Customer Profile**

**Code (Line 140-144):**
```php
Customer::create([
    'user_id' => $user->id,
    'name' => $googleUser->getName(),
    'email' => $googleUser->getEmail(),
]);
```

**Database Insert:**
```sql
INSERT INTO customers (
    user_id, 
    name, 
    email, 
    phone, 
    is_guest, 
    created_at, 
    updated_at
) VALUES (
    100,
    'John Doe',
    'john@gmail.com',
    NULL,
    0,
    '2026-02-12 10:30:00',
    '2026-02-12 10:30:00'
);
-- Returns: id = 50
```

#### **Step 8A: Login User**

**Code (Line 162-166):**
```php
Auth::login($user, true); // Remember user
request()->session()->regenerate();
request()->session()->forget('url.intended');

return redirect()->route('customer.dashboard')
    ->with('status', 'Welcome! Your account has been created successfully.');
```

**Session Created:**
```
Session ID: random_session_id
User ID: 100
Remember Token: stored in database
```

---

### **SCENARIO B: Existing Customer (Returning)**

#### **Step 4B: Update Google ID (if not set)**

**Code (Line 66-69):**
```php
if (!$user->google_id) {
    $user->google_id = $googleUser->getId();
}
```

**Database Update:**
```sql
UPDATE users 
SET google_id = '1234567890'
WHERE id = 100 AND google_id IS NULL;
```

#### **Step 5B: Ensure Customer Role**

**Code (Line 71-73):**
```php
$user->role_id = $customerRole->id;
$user->save();
```

**Database Update:**
```sql
UPDATE users 
SET role_id = 3
WHERE id = 100;
```

#### **Step 6B: Ensure Customer Profile Exists**

**Code (Line 76-86):**
```php
if (!$user->customer) {
    Customer::create([
        'user_id' => $user->id,
        'name' => $user->name ?? $googleUser->getName(),
        'email' => $user->email,
        'phone' => $user->phone ?? null,
    ]);
    
    $user = User::with(['customer', 'role'])->find($user->id);
}
```

**Database Insert (only if missing):**
```sql
-- Check first
SELECT * FROM customers WHERE user_id = 100;

-- If not found, insert
INSERT INTO customers (user_id, name, email, phone, is_guest, created_at, updated_at)
VALUES (100, 'John Doe', 'john@gmail.com', NULL, 0, NOW(), NOW());
```

#### **Step 7B: Login User**

**Code (Line 104-108):**
```php
Auth::login($user, true);
request()->session()->regenerate();
request()->session()->forget('url.intended');

return redirect()->route('customer.dashboard')
    ->with('status', 'Welcome back!');
```

---

## 📈 Database Relationships

```
┌─────────────────┐
│     roles       │
│─────────────────│
│ id (PK)         │
│ name            │ ← "customer"
│ guard_name      │
└────────┬────────┘
         │
         │ role_id (FK)
         │
         ▼
┌─────────────────┐          ┌─────────────────┐
│     users       │          │   customers     │
│─────────────────│          │─────────────────│
│ id (PK)         │──────────│ user_id (FK)    │
│ name            │ 1      1 │ name            │
│ email           │          │ email           │
│ google_id       │ ← UNIQUE │ phone           │
│ password        │          │ is_guest        │
│ role_id (FK)    │          │ address         │
│ email_verified  │          └─────────────────┘
└─────────────────┘
         │
         │ model_id (FK)
         │
         ▼
┌─────────────────┐
│model_has_roles  │ ← Spatie Permissions
│─────────────────│
│ role_id (FK)    │
│ model_type      │
│ model_id (FK)   │
└─────────────────┘
```

---

## 🔍 Verification Queries

### **Check User Created with Google OAuth:**
```sql
SELECT 
    u.id,
    u.name,
    u.email,
    u.google_id,
    u.email_verified_at,
    r.name as role_name,
    c.id as customer_id
FROM users u
LEFT JOIN roles r ON u.role_id = r.id
LEFT JOIN customers c ON u.id = c.user_id
WHERE u.google_id IS NOT NULL;
```

### **Check Customer Profile Linked:**
```sql
SELECT 
    c.*,
    u.email,
    u.google_id
FROM customers c
INNER JOIN users u ON c.user_id = u.id
WHERE u.google_id IS NOT NULL;
```

### **Check Spatie Role Assignment:**
```sql
SELECT 
    u.id,
    u.name,
    u.email,
    r.name as assigned_role
FROM users u
INNER JOIN model_has_roles mhr ON u.id = mhr.model_id
INNER JOIN roles r ON mhr.role_id = r.id
WHERE u.google_id IS NOT NULL
  AND mhr.model_type = 'App\\Models\\User';
```

---

## ✅ Testing Steps

### **1. Test New Customer Registration**

**Steps:**
1. Make sure you're logged out
2. Go to: `http://localhost:8000/login`
3. Click "Sign in with Google"
4. Select a Google account
5. Authorize the app

**Expected Database Changes:**
```sql
-- New record in users table
INSERT INTO users (name, email, google_id, role_id, email_verified_at, ...)

-- New record in customers table  
INSERT INTO customers (user_id, name, email, is_guest, ...)

-- New record in model_has_roles table
INSERT INTO model_has_roles (role_id, model_type, model_id)
```

**Verify:**
```bash
php test-google-oauth-database.php
```

### **2. Test Existing Customer Login**

**Steps:**
1. Logout
2. Login with Google again (same account)
3. Should redirect to dashboard

**Expected Database Changes:**
```sql
-- NO new records created
-- Existing user session refreshed
```

### **3. Test Email→Google Conversion**

**Steps:**
1. Create account with email/password: john@gmail.com
2. Logout
3. Login with Google using same email: john@gmail.com

**Expected Database Changes:**
```sql
-- Update existing user with google_id
UPDATE users SET google_id = '1234567890' WHERE email = 'john@gmail.com';
```

---

## 🛡️ Security Features

### **1. Email Verification**
- Google OAuth users have `email_verified_at` automatically set
- No need to send verification emails

### **2. Password Security**
- Random 32-character password generated
- Google users cannot login with password (don't know it)
- Can reset password if they want email/password access later

### **3. Unique Constraints**
- `google_id` is unique across all users
- Prevents duplicate accounts from same Google ID

### **4. Role Enforcement**
- Always assigns 'customer' role
- Staff/admin cannot login via Google OAuth on customer pages

---

## 🐛 Troubleshooting

### **Issue: "Customer role not found"**

**Solution:**
```bash
php artisan db:seed --class=RoleSeeder
```

### **Issue: Google ID not saving**

**Check:**
1. Migration ran: `php artisan migrate:status`
2. Model fillable: Check `app/Models/User.php` line 36-46
3. Column exists: `php test-google-oauth-database.php`

### **Issue: Customer profile not created**

**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

**Look for:**
```
Google OAuth: New customer profile creation failed
```

---

## 📝 Summary

**When a customer logs in with Google OAuth:**

1. ✅ Their info is retrieved from Google (name, email, ID)
2. ✅ System checks if user exists by email or google_id
3. ✅ **New users**: Creates User + Customer records
4. ✅ **Existing users**: Updates google_id, ensures customer profile exists
5. ✅ User is logged in automatically
6. ✅ Redirected to customer dashboard
7. ✅ Email is pre-verified

**Database Tables Updated:**
- `users` table ← Main account
- `customers` table ← Profile info
- `model_has_roles` table ← Role assignment

**All data is stored permanently** and can be used for:
- Future logins
- Booking management
- Customer profile
- Order history
- Communication

---

## 🎓 Next Steps

1. **Configure `.env`** with your Google OAuth credentials
2. **Test the flow** with a real Google account
3. **Run verification:** `php test-google-oauth-database.php`
4. **Check database** to see the stored data
5. **Monitor logs** at `storage/logs/laravel.log`

---

## 📞 Support

If you encounter issues:
1. Check `storage/logs/laravel.log`
2. Run `php test-google-oauth-database.php`
3. Verify Google OAuth credentials in `.env`
4. Ensure redirect URI matches in Google Cloud Console

---

**Created:** February 12, 2026  
**Controller:** `app/Http/Controllers/Auth/GoogleAuthController.php`  
**Routes:** `routes/auth.php` (lines 40-46)
