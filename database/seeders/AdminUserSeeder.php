<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = 'admin@biblio.com'; // Admin email as requested

        $existingAdmin = User::where('email', $adminEmail)->first();

        if (!$existingAdmin) {
            User::create([
                'name' => 'Administrateur Bibliothèque',
                'email' => $adminEmail,
                'password' => Hash::make('12345'), // Password as requested
                'role' => 'admin', // Set role to admin
            ]);
            $this->command->info("Admin user with email {$adminEmail} created successfully.");
        } else {
            $this->command->info("Admin user with email {$adminEmail} already exists.");
        }
    }
}
