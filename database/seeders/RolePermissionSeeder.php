<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::whereName("admin")->first();
        $roles = ["admin", "user", "teacher"];
        foreach ($roles as $roleName) {
            $role = Role::whereName($roleName)->first();
            $admin->assignRole($role);
        }
    }
}
