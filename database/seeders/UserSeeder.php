<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@cafe.com'],
            [
                'name' => 'Owner',
                'password' => bcrypt('12345678'),
                'role' => 'owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@cafe.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('12345678'),
                'role' => 'manager',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@cafe.com'],
            [
                'name' => 'Cashier',
                'password' => bcrypt('12345678'),
                'role' => 'cashier',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kitchen@cafe.com'],
            [
                'name' => 'Kitchen',
                'password' => bcrypt('12345678'),
                'role' => 'kitchen',
            ]
        );
    }
}