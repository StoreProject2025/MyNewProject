<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create a test customer with credit balance
$email = 'haniiiin11117@gmail.com';
$password = 'customer123';
$name = 'Test Customer';

// Check if the user already exists
$existingUser = User::where('email', $email)->first();

if ($existingUser) {
    echo "Customer already exists, updating credit...\n";
    $customer = $existingUser;
} else {
    // Create a new customer
    $customer = new User();
    $customer->name = $name;
    $customer->email = $email;
    $customer->password = Hash::make($password);
    $customer->email_verified_at = now(); // Automatically verify the email
    $customer->save();
    
    echo "Created new customer with ID: " . $customer->id . "\n";
}

// Set customer credit
$customer->credit = 500;
$customer->save();

echo "\n==============================================\n";
echo "TEST CUSTOMER CREATED SUCCESSFULLY\n";
echo "==============================================\n";
echo "Email: {$email}\n";
echo "Password: {$password}\n";
echo "Credit Balance: $" . number_format($customer->credit, 2) . "\n";
echo "==============================================\n";
echo "Use this account to test customer processes.\n"; 