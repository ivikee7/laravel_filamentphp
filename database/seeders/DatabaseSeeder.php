<?php

namespace Database\Seeders;

use App\Models\Store\Store;
use App\Models\StudentClass;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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


        Store::create([
            'name' => 'India Book Centre',
            'address' => 'Kankarbagh, Patna',
            'contact' => 8758687686,
            'creator_id' => 1,
            'updater_id' => 1,
        ]);
        Store::create([
            'name' => 'Shri Ram Centennial School',
            'address' => 'Bhogipur, Near Shahpur, Jaganpura, Patna',
            'contact' => 8686876876,
            'creator_id' => 1,
            'updater_id' => 1,
        ]);

        StudentClass::create(['name' => 'Nursery']);
        StudentClass::create(['name' => 'LKG']);
        StudentClass::create(['name' => 'UKG']);
        StudentClass::create(['name' => 'STD-1']);
        StudentClass::create(['name' => 'STD-2']);
        StudentClass::create(['name' => 'STD-3']);
        StudentClass::create(['name' => 'STD-4']);
        StudentClass::create(['name' => 'STD-5']);
        StudentClass::create(['name' => 'STD-6']);
        StudentClass::create(['name' => 'STD-7']);
        StudentClass::create(['name' => 'STD-8']);
        StudentClass::create(['name' => 'STD-9']);
        StudentClass::create(['name' => 'STD-10']);
        StudentClass::create(['name' => 'STD-11']);
        StudentClass::create(['name' => 'STD-12']);
    }
}
