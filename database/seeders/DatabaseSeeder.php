<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

    \Modules\User\Database\Factories\UserFactory::new()->create([
        'name' => 'admin',
        'email' => 'admin@gmail.com',
        'phone' => (int) '+989903001905',
        'password' => Hash::make('password')
    ]);

    }
}
