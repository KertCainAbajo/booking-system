<?php

/**
 * Real-time Google OAuth Test Monitor
 * Run this while testing OAuth to see database changes
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Customer;

echo "=== Google OAuth Live Testing Monitor ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Check current users with Google ID
echo "📊 Current Google OAuth Users in Database:\n";
echo str_repeat('-', 80) . "\n";

$googleUsers = User::whereNotNull('google_id')
    ->with(['customer', 'role'])
    ->orderBy('created_at', 'desc')
    ->get();

if ($googleUsers->count() > 0) {
    foreach ($googleUsers as $user) {
        echo "User ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Google ID: {$user->google_id}\n";
        echo "Role: " . ($user->role ? $user->role->name : 'N/A') . "\n";
        echo "Customer Profile: " . ($user->customer ? "Yes (ID: {$user->customer->id})" : 'No') . "\n";
        echo "Email Verified: " . ($user->email_verified_at ? 'Yes ✓' : 'No') . "\n";
        echo "Created: {$user->created_at->format('Y-m-d H:i:s')}\n";
        echo "Last Updated: {$user->updated_at->format('Y-m-d H:i:s')}\n";
        echo str_repeat('-', 80) . "\n";
    }
} else {
    echo "No Google OAuth users found yet.\n";
    echo "After successfully logging in with Google, run this script again to see the new user.\n";
}

echo "\n📈 Statistics:\n";
echo "Total Users with Google OAuth: " . $googleUsers->count() . "\n";
echo "Total Customers: " . Customer::count() . "\n";
echo "Total Users: " . User::count() . "\n";

echo "\n🔍 Latest 5 Users Created (any type):\n";
echo str_repeat('-', 80) . "\n";
$latestUsers = User::with(['customer', 'role'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($latestUsers as $user) {
    echo "#{$user->id} - {$user->name} ({$user->email}) - ";
    echo "Role: " . ($user->role ? $user->role->name : 'N/A') . " - ";
    echo "Google: " . ($user->google_id ? '✓' : '✗') . " - ";
    echo "Created: {$user->created_at->diffForHumans()}\n";
}

echo "\n" . str_repeat('=', 80) . "\n";
echo "✓ Monitor complete. Run this script again after testing Google OAuth.\n";
