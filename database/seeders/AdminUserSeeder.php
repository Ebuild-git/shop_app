<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'lastname' => 'Admin',
            'firstname' => 'User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'phone_number' => '1234567890',
            'ip_address' => '127.0.0.1',
            'avatar' => 'avatar.png',
            'address' => '123 Admin Street',
            'region' => null,
            'role' => 'admin',
            'type' => 'admin',
            'email_verified_at' => Carbon::now(),
            'validate_at' => Carbon::now(),
            'first_login_at' => null,
            'last_login_at' => null,
            'photo_verified_at' => null,
            'matricule' => null,
            'birthdate' => '1990-01-01',
            'gender' => 'male',
            'locked' => false,
            'password' => Hash::make('password'), // Change 'password' to the desired password
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
