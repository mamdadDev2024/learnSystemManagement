<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Course\Database\Seeders\CourseDatabaseSeeder;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = UserFactory::new()->create([
            "name" => "Admin User",
            "email" => "admin@gmail.com",
            "phone" => "+989903001905",
            "password" => Hash::make('password'),
            "email_verified_at" => now(),
        ]);

        $user = UserFactory::new()->create([
            "name" => "Test User",
            "email" => "user@gmail.com",
            "phone" => "+989123456789",
            "password" => Hash::make('password'),
            "email_verified_at" => now(),
        ]);

        $roles = ["admin", "user", "teacher"];
        foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName , 'guard_name' => 'api'])->first();
            $admin->assignRole($role);
        }
        $this->call([
            UserDatabaseSeeder::class,
            CourseDatabaseSeeder::class,
        ]);

        if ($adminRole = Role::where('name', 'admin')->first()) {
            $admin->assignRole($adminRole);
        }

        if ($userRole = Role::where('name', 'user')->first()) {
            $user->assignRole($userRole);
        }

        if (app()->environment('local', 'development')) {
            $this->call([
                \Modules\Lesson\Database\Seeders\LessonDatabaseSeeder::class,
            ]);

            UserFactory::new()->count(10)->create()->each(function ($user) {
                if ($userRole = Role::where('name', 'user')->first()) {
                    $user->assignRole($userRole);
                }
            });

            $this->command->info('testing data seeded successfully!');
        }

        $this->command->info('🎉 Seeding completed successfully!');
        $this->command->info('📧 Admin Login: admin@gmail.com / password');
        $this->command->info('📧 User Login: user@gmail.com / password');
    }
}
