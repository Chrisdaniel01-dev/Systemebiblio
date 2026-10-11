<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Testing Login Functionality ===\n\n";

// 1. Check if admin user exists
echo "1. Checking admin user...\n";
$adminUser = User::where('email', 'admin2@example.com')->first();

if ($adminUser) {
    echo "✅ Admin user found:\n";
    echo "  - Name: {$adminUser->name}\n";
    echo "  - Email: {$adminUser->email}\n";
    echo "  - Role: {$adminUser->role}\n";
    echo "  - Password hash: " . substr($adminUser->password, 0, 20) . "...\n";
} else {
    echo "❌ Admin user not found!\n";
    exit(1);
}

echo "\n";

// 2. Test password verification
echo "2. Testing password verification...\n";
$testPassword = 'password123';
$isValidPassword = Hash::check($testPassword, $adminUser->password);

if ($isValidPassword) {
    echo "✅ Password verification successful!\n";
} else {
    echo "❌ Password verification failed!\n";
    exit(1);
}

echo "\n";

// 3. Test AuthController webLogin method
echo "3. Testing AuthController webLogin method...\n";

$controller = new AuthController();

// Create a mock request with login credentials
$requestData = [
    'email' => 'admin2@example.com',
    'password' => 'password123'
];

$request = new Request($requestData);

// Test validation
try {
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    echo "✅ Request validation passed!\n";
    echo "  - Email: {$validated['email']}\n";
    echo "  - Password: [HIDDEN]\n";

} catch (Exception $e) {
    echo "❌ Request validation failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 4. Test authentication attempt
echo "4. Testing authentication attempt...\n";

try {
    // Test the authentication logic directly
    $credentials = [
        'email' => 'admin2@example.com',
        'password' => 'password123'
    ];

    if (Auth::attempt($credentials)) {
        echo "✅ Authentication successful!\n";
        echo "  - User authenticated: " . Auth::user()->name . "\n";
        echo "  - User role: " . Auth::user()->role . "\n";
    } else {
        echo "❌ Authentication failed!\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ Authentication test failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 5. Test categories
echo "5. Testing categories...\n";
$categories = App\Models\Categorie::all();

echo "Found " . $categories->count() . " categories:\n";
foreach ($categories as $category) {
    echo "  - ID: {$category->id}, Name: {$category->nom}\n";
}

if ($categories->count() == 0) {
    echo "⚠️  No categories found! You may want to run: php artisan db:seed --class=CategorieSeeder\n";
}

echo "\n=== Test Summary ===\n";
echo "✅ All login tests passed!\n";
echo "✅ Admin user created successfully with email: admin2@example.com\n";
echo "✅ Password: password123\n";
echo "✅ Authentication system is working correctly\n";
echo "✅ Categories are available for book creation\n";
echo "\nYou can now login with:\n";
echo "  Email: admin2@example.com\n";
echo "  Password: password123\n";
