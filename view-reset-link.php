<?php

/**
 * View the latest password reset link from the log file
 * Run with: php view-reset-link.php
 */

$logFile = __DIR__ . '/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "❌ Log file not found: {$logFile}\n";
    exit(1);
}

// Read the log file
$logContent = file_get_contents($logFile);

// Find all password reset URLs
preg_match_all('/http:\/\/[^\s]+\/reset-password\/[^\s"]+/', $logContent, $matches);

if (empty($matches[0])) {
    echo "❌ No password reset links found in the log file.\n";
    echo "💡 Try requesting a password reset first at: http://127.0.0.1:8000/forgot-password\n";
    exit(1);
}

// Get the most recent link
$resetLinks = array_unique($matches[0]);
$latestLink = end($resetLinks);

// Clean up any HTML tags or extra characters
$latestLink = strip_tags($latestLink);
$latestLink = trim($latestLink);

echo "\n✅ Latest Password Reset Link Found:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "{$latestLink}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (count($resetLinks) > 1) {
    echo "📋 Total links found: " . count($resetLinks) . "\n";
    echo "💡 Showing the most recent one.\n\n";
}

echo "🌐 Open this link in your browser to reset the password.\n\n";
