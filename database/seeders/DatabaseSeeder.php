<?php

namespace Database\Seeders;

use App\Models\AcadamicSession;
use App\Models\AcademicYear;
use App\Models\AdmissionClass;
use App\Models\AdmissionSection;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
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
        Role::create(['name' => 'Owner']);
        Role::create(['name' => 'Principal']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Teacher']);
        Role::create(['name' => 'Student']);
        Role::create(['name' => 'Driver']);
        Role::create(['name' => 'Conductor']);
        Role::create(['name' => 'Maid']);
        Role::create(['name' => 'Gardener']);
        Role::create(['name' => 'Ground Staff']);
        Role::create(['name' => 'Security Guard']);
        Role::create(['name' => 'Supervisor']);

        AcademicYear::create([
            'name' => '2025-2026',
            'start_date' => '2025-04-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
            'creator_id' => 1,
            'updater_id' => 1
        ]);

        Classes::create(['name' => 'Nursery', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'LKG', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'UKG', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-1', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-2', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-3', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-4', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-5', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-6', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-7', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-8', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-9', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-10', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-11', 'creator_id' => 1, 'updater_id' => 1]);
        Classes::create(['name' => 'STD-12', 'creator_id' => 1, 'updater_id' => 1]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'ivikee7@gmail.com',
            'password' => 'admin@123...!@',
            'is_active' => true,
        ])->assignRole('Super Admin');
        User::factory()->create([
            'name' => 'Chanchal Jha',
            'email' => 'chanchaljha@srcspatna.com',
            'password' => 'password',
            'is_active' => true,
        ])->assignRole('Admin');
        User::factory()->create([
            'name' => 'Teacher',
            'email' => 'Teacher@gmail.com',
            'password' => 'password',
            'is_active' => true,
        ])->assignRole('Teacher');
        $student = User::factory()->create([
            'name' => 'Student',
            'email' => 'Student@gmail.com',
            'password' => 'password',
            'is_active' => true,
        ])->assignRole('Student');
        Student::create([
            'user_id' => $student->id,
            'admission_number'=>$student->id,
            'admission_date'=>now(),
            // 'current_status',
            // 'tc_status',
            // 'leaving_date',
            // 'exit_reason',
        ]);
    }
}
