<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewAdmins extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'lastname' => 'Admin1',
            'firstname' => 'User',
            'email' => 'admin1@shopin.com',
            'username' => 'admin1',
            'phone_number' => '1234567890',
            'ip_address' => '127.0.0.1',
            'avatar' => 'avatar.png',
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
            'password' => Hash::make('YM$Hxu-zY3c4^QUw'), // Change to a new password after first login
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'lastname' => 'Admin2',
            'firstname' => 'User',
            'email' => 'admin2@shopin.com',
            'username' => 'admin2',
            'phone_number' => '1234567890',
            'ip_address' => '127.0.0.1',
            'avatar' => 'avatar.png',
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
            'password' => Hash::make('W9UN9$i%47b#8Jk*'), // Change to a new password after first login
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
