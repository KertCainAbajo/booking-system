<?php

/**
 * Quick test script to verify password reset email functionality
 * Run with: php test-password-reset.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Password;
use App\Models\User;

// Find a test customer user
$user = User::whereHas('role', function($query) {
    $query->where('name', 'customer');
})->first();

if (!$user) {
    echo "❌ No customer user found. Create a customer account first.\n";
    exit(1);
}

echo "✅ Testing password reset for: {$user->email}\n";

// Send password reset link
$status = Password::sendResetLink(['email' => $user->email]);

if ($status === Password::RESET_LINK_SENT) {
    echo "✅ Password reset email sent successfully!\n";
    echo "📧 Check the email inbox for: {$user->email}\n";
    echo "📝 If using 'log' driver, check: storage/logs/laravel.log\n";
} else {
    echo "❌ Failed to send password reset email.\n";
    echo "Error: " . __($status) . "\n";
}
