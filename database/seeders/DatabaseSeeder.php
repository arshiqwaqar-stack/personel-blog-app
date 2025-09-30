<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
  public function run(): void
    {
        // 1. Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // 2. Create 10 random users and assign "user" role
        User::factory(10)->create()->each(function ($user) use ($userRole) {
            $user->assignRole($userRole);
        });

        // 3. Optionally create a test admin user
        
        User::findOrFail('1')->assignRole($adminRole);
    }
}
