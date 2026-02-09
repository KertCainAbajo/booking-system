<?php
/**
 * Customer 2FA Test Script
 * 
 * Run this in Laravel Tinker to test the customer login with 2FA:
 * php artisan tinker
 * include 'test-customer-2fa.php';
 */

use App\Models\User;
use App\Models\Customer;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "=== Customer 2FA Test Setup ===\n\n";

// 1. Check if customer role exists or create one
echo "1. Checking for customer role...\n";
$customerRole = Role::firstOrCreate(
    ['name' => 'customer'],
    ['description' => 'Customer with login access']
);
echo "   ✓ Customer role ready (ID: {$customerRole->id})\n\n";

// 2. Create a test customer user
echo "2. Creating test customer user...\n";
$testEmail = 'test.customer@example.com';

// Remove existing test user if exists
$existingUser = User::where('email', $testEmail)->first();
if ($existingUser) {
    echo "   ! Test user already exists, deleting...\n";
    $existingUser->customer()->delete();
    $existingUser->delete();
}

// Create new test user
$user = User::create([
    'name' => 'Test Customer',
    'email' => $testEmail,
    'password' => Hash::make('password123'),
    'role_id' => $customerRole->id,
    'phone' => '555-0123',
]);

echo "   ✓ User created (ID: {$user->id})\n";
echo "     Email: {$user->email}\n";
echo "     Password: password123\n\n";

// 3. Create customer record
echo "3. Creating customer record...\n";
$customer = Customer::create([
    'user_id' => $user->id,
    'name' => 'Test Customer',
    'email' => $testEmail,
    'phone' => '555-0123',
    'is_guest' => false,
]);

echo "   ✓ Customer created (ID: {$customer->id})\n\n";

// 4. Verify customer relationship
echo "4. Verifying user-customer relationship...\n";
$user->load('customer');
if ($user->customer) {
    echo "   ✓ User has customer relationship\n";
    echo "     Customer Name: {$user->customer->name}\n\n";
} else {
    echo "   ✗ ERROR: Customer relationship not found!\n\n";
}

// 5. Display test account info
echo "=== TEST ACCOUNT READY ===\n\n";
echo "Login URL: http://localhost:8000/customer/login\n";
echo "Email: {$testEmail}\n";
echo "Password: password123\n\n";

echo "2FA Setup URL: http://localhost:8000/customer/2fa/setup\n\n";

echo "=== Next Steps ===\n";
echo "1. Visit the login URL above\n";
echo "2. Login with the test credentials\n";
echo "3. Navigate to the 2FA setup page\n";
echo "4. Scan the QR code with Google Authenticator\n";
echo "5. Test 2FA login\n\n";

echo "To check 2FA status:\n";
echo "User::where('email', '{$testEmail}')->first()->google2fa_enabled\n\n";

echo "To disable 2FA for testing:\n";
echo "\$user = User::where('email', '{$testEmail}')->first();\n";
echo "\$user->update(['google2fa_enabled' => false, 'google2fa_secret' => null]);\n\n";
