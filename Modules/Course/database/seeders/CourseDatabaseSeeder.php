<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Database\Factories\CourseFactory;
use Modules\User\Models\User;

class CourseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            CourseFactory::new()
                ->count(rand(1, 5))
                ->create([
                    "user_id" => $user->id,
                ]);
        }
    }
}
