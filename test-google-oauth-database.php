<?php

/**
 * Test script to verify Google OAuth database setup
 * Run: php test-google-oauth-database.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Schema;

echo "=== Google OAuth Database Verification ===\n\n";

// 1. Check if google_id column exists in users table
echo "1. Checking users table structure...\n";
if (Schema::hasColumn('users', 'google_id')) {
    echo "   ✓ google_id column exists in users table\n";
} else {
    echo "   ✗ google_id column MISSING in users table\n";
    echo "   Run: php artisan migrate\n";
}

// 2. Check if google_id is fillable in User model
echo "\n2. Checking User model configuration...\n";
$user = new User();
if (in_array('google_id', $user->getFillable())) {
    echo "   ✓ google_id is fillable in User model\n";
} else {
    echo "   ✗ google_id is NOT fillable in User model\n";
}

// 3. Check for any users with Google ID
echo "\n3. Checking for existing Google OAuth users...\n";
$googleUsers = User::whereNotNull('google_id')->with('customer')->get();
if ($googleUsers->count() > 0) {
    echo "   Found " . $googleUsers->count() . " user(s) with Google OAuth:\n\n";
    foreach ($googleUsers as $user) {
        echo "   User ID: {$user->id}\n";
        echo "   Name: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Google ID: {$user->google_id}\n";
        echo "   Has Customer Profile: " . ($user->customer ? 'Yes' : 'No') . "\n";
        if ($user->customer) {
            echo "   Customer ID: {$user->customer->id}\n";
        }
        echo "   ---\n";
    }
} else {
    echo "   No Google OAuth users found yet (normal for new setup)\n";
}

// 4. Check Google configuration
echo "\n4. Checking Google OAuth configuration...\n";
$clientId = config('services.google.client_id');
$clientSecret = config('services.google.client_secret');
$redirectUri = config('services.google.redirect');

if ($clientId && $clientSecret) {
    echo "   ✓ Google Client ID configured\n";
    echo "   ✓ Google Client Secret configured\n";
    echo "   ✓ Redirect URI: {$redirectUri}\n";
} else {
    echo "   ✗ Google OAuth NOT configured in .env\n";
    echo "   Add these to .env:\n";
    echo "   GOOGLE_CLIENT_ID=your-client-id\n";
    echo "   GOOGLE_CLIENT_SECRET=your-client-secret\n";
}

// 5. Check routes
echo "\n5. Checking OAuth routes...\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $hasGoogleRoute = false;
    $hasCallbackRoute = false;
    
    foreach ($routes as $route) {
        if ($route->getName() === 'auth.google') {
            $hasGoogleRoute = true;
        }
        if ($route->getName() === 'auth.google.callback') {
            $hasCallbackRoute = true;
        }
    }
    
    if ($hasGoogleRoute) {
        echo "   ✓ auth.google route exists\n";
    }
    if ($hasCallbackRoute) {
        echo "   ✓ auth.google.callback route exists\n";
    }
} catch (\Exception $e) {
    echo "   Could not verify routes\n";
}

echo "\n=== Verification Complete ===\n";
echo "\nNext Steps:\n";
echo "1. Configure Google OAuth credentials in .env (if not done)\n";
echo "2. Visit: http://localhost:8000/login\n";
echo "3. Click 'Sign in with Google' button\n";
echo "4. Check database for new user with google_id\n";
echo "5. Re-run this script to see the Google user in the database\n";
