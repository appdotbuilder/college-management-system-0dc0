<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dmmProgram = Program::where('code', 'DMM')->first();
        $doeProgram = Program::where('code', 'DOE')->first();

        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@college.edu'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@college.edu',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Admin
        User::firstOrCreate(
            ['email' => 'admin@college.edu'],
            [
                'name' => 'Administrator',
                'email' => 'admin@college.edu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Staff
        User::firstOrCreate(
            ['email' => 'staff@college.edu'],
            [
                'name' => 'Staff Member',
                'email' => 'staff@college.edu',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );

        // Create Students
        User::firstOrCreate(
            ['email' => 'student1@college.edu'],
            [
                'name' => 'John Smith',
                'email' => 'student1@college.edu',
                'password' => Hash::make('password'),
                'role' => 'student',
                'nric_no' => 'S1234567A',
                'program_id' => $dmmProgram?->id,
                'intake_date' => now()->subMonths(6),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'student2@college.edu'],
            [
                'name' => 'Jane Doe',
                'email' => 'student2@college.edu',
                'password' => Hash::make('password'),
                'role' => 'student',
                'nric_no' => 'S7654321B',
                'program_id' => $doeProgram?->id,
                'intake_date' => now()->subMonths(3),
                'email_verified_at' => now(),
            ]
        );
    }
}