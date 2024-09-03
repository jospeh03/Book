<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the admin user already exists
        $user = User::where('email', 'admin@example.com')->first();

        if (!$user) {
            // Create the admin user
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Change this to a secure password
                'role' => 'admin', // Assuming you have a 'role' column
            ]);
        }
    }
}
