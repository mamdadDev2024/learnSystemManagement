<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Course\Database\Seeders\CourseDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \Modules\User\Database\Factories\UserFactory::new()->create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "phone" => "+989903001905",
        ]);
        $this->call([
            UserDatabaseSeeder::class,
            CourseDatabaseSeeder::class,
            RolePermissionSeeder::class,
            // Add other seeders here
        ]);
    }
}
