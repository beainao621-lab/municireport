<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@municireport.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@municireport.com',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'phone'    => null,
                'location' => null,
            ]
        );
    }
}