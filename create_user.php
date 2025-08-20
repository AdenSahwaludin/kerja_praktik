<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create test user
$user = User::firstOrCreate(
    ['email' => 'test@example.com'],
    [
        'nama' => 'Test User',
        'kata_sandi' => Hash::make('password123'),
    ]
);

echo "User created: " . $user->email . "\n";
