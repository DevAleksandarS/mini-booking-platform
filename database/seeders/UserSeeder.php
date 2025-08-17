<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => env('FIRST_USER_NAME'),
            'email' => env('FIRST_USER_EMAIL'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('FIRST_USER_PASSWORD')),
            'role' => 'admin',
        ]);
    }
}
