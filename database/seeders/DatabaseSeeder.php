<?php

namespace Database\Seeders;

use App\Models\AcadamicSession;
use App\Models\AcademicYear;
use App\Models\AdmissionClass;
use App\Models\AdmissionSection;
use App\Models\BloodGroup;
use App\Models\Classes;
use App\Models\Gender;
use App\Models\Quota;
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

        $academicYear = AcademicYear::create([
            'name' => '2025-2026',
            'start_date' => '2025-04-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
            'created_by' => 1,
        ]);

        Quota::create(['name' => 'RTE', 'discount_percentage' => 100, 'created_by' => 1]);
        Quota::create(['name' => 'Board Members', 'discount_percentage' => 100, 'created_by' => 1]);
        Quota::create(['name' => 'Staff', 'discount_percentage' => 30, 'created_by' => 1]);
        Quota::create(['name' => 'Teacher', 'discount_percentage' => 30, 'created_by' => 1]);
        Quota::create(['name' => 'Admin', 'discount_percentage' => 30, 'created_by' => 1]);
        Quota::create(['name' => 'Free', 'discount_percentage' => 100, 'created_by' => 1]);

        BloodGroup::create(['name' => 'A+', 'created_by' => 1]);
        BloodGroup::create(['name' => 'A-', 'created_by' => 1]);
        BloodGroup::create(['name' => 'B+', 'created_by' => 1]);
        BloodGroup::create(['name' => 'B-', 'created_by' => 1]);
        BloodGroup::create(['name' => 'AB+', 'created_by' => 1]);
        BloodGroup::create(['name' => 'AB-', 'created_by' => 1]);
        BloodGroup::create(['name' => 'O+', 'created_by' => 1]);
        BloodGroup::create(['name' => 'O-', 'created_by' => 1]);

        Gender::create(['name' => 'Male', 'created_by' => 1]);
        Gender::create(['name' => 'Female', 'created_by' => 1]);
        Gender::create(['name' => 'Other', 'created_by' => 1]);

        Classes::create(['name' => 'Nursery', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'LKG', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'UKG', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-1', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-2', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-3', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-4', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-5', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-6', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-7', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-8', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-9', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-10', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-11', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);
        Classes::create(['name' => 'STD-12', 'academic_year_id' => $academicYear->id, 'created_by' => 1]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'ivikee7@gmail.com',
            'password' => 'admin@123...!@',
            'is_active' => true,
            'created_by' => 1,
        ])->assignRole('Super Admin');
        User::factory()->create([
            'name' => 'Chanchal Jha',
            'email' => 'chanchaljha@srcspatna.com',
            'password' => 'password',
            'is_active' => true,
            'created_by' => 1,
        ])->assignRole('Admin');
        User::factory()->create([
            'name' => 'Teacher',
            'email' => 'Teacher@gmail.com',
            'password' => 'password',
            'is_active' => true,
            'created_by' => 1,
        ])->assignRole('Teacher');
        $student = User::factory()->create([
            'name' => 'Student',
            'email' => 'Student@gmail.com',
            'password' => 'password',
            'is_active' => true,
            'created_by' => 1,
        ])->assignRole('Student');
        Student::create([
            'user_id' => $student->id,
            'admission_number' => $student->id,
            'admission_date' => now(),
            'quota_id' => 1,
            // 'current_status',
            // 'tc_status',
            // 'leaving_date',
            // 'exit_reason',
            'created_by' => 1,
        ]);
    }
}
