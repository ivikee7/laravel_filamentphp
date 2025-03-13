<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Teacher']);
        Role::create(['name' => 'Student']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'ivikee7@gmail.com',
            'password' => 'admin@123...!@',
        ])->assignRole('Super Admin');
        User::factory()->create([
            'name' => 'Teacher',
            'email' => 'Teacher@gmail.com',
            'password' => 'password',
        ])->assignRole('Teacher');
        User::factory()->create([
            'name' => 'Student',
            'email' => 'Student@gmail.com',
            'password' => 'password',
        ])->assignRole('Student');
    }
}
