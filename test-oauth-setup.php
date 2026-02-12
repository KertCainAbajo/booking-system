<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\User;

echo "=== OAuth Setup Test ===\n\n";

// Check customer role
$customerRole = Role::where('name', 'customer')->first();
if ($customerRole) {
    echo "✓ Customer role exists (ID: {$customerRole->id})\n";
} else {
    echo "✗ Customer role NOT FOUND\n";
    echo "   Creating customer role...\n";
    $customerRole = Role::create(['name' => 'customer']);
    echo "✓ Customer role created (ID: {$customerRole->id})\n";
}

// Check if there are any users with Google ID
$googleUsers = User::whereNotNull('google_id')->with('customer', 'role')->get();
echo "\n✓ Found {$googleUsers->count()} users with Google OAuth\n";

foreach ($googleUsers as $user) {
    echo "   - {$user->email} (ID: {$user->id})\n";
    echo "     Role: " . ($user->role ? $user->role->name : 'NONE') . "\n";
    echo "     Customer Profile: " . ($user->customer ? 'YES' : 'NO') . "\n";
}

echo "\n=== Test Complete ===\n";
