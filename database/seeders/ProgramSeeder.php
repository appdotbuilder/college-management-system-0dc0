<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Diploma Maritime Management',
                'code' => 'DMM',
                'description' => 'A comprehensive program focusing on maritime business management, port operations, and shipping logistics.',
                'is_active' => true,
            ],
            [
                'name' => 'Diploma Offshore Engineering',
                'code' => 'DOE',
                'description' => 'Specialized program in offshore oil and gas engineering, covering drilling operations and marine structures.',
                'is_active' => true,
            ],
            [
                'name' => 'Certificate in Marine Engineering',
                'code' => 'CME',
                'description' => 'Foundational program in marine engineering principles and ship maintenance.',
                'is_active' => true,
            ],
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate(
                ['code' => $program['code']],
                $program
            );
        }
    }
}