<?php

namespace Database\Seeders;

use App\Models\FeeType;
use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeTypes = [
            [
                'name' => 'Registration Fee',
                'description' => 'One-time registration fee for new students',
                'default_amount' => 500.00,
                'is_active' => true,
            ],
            [
                'name' => 'Tuition Fee',
                'description' => 'Monthly tuition fee for academic courses',
                'default_amount' => 2500.00,
                'is_active' => true,
            ],
            [
                'name' => 'Transportation Fee',
                'description' => 'Monthly transportation fee for campus shuttle',
                'default_amount' => 150.00,
                'is_active' => true,
            ],
            [
                'name' => 'Hostel Fee',
                'description' => 'Monthly accommodation fee for on-campus hostel',
                'default_amount' => 800.00,
                'is_active' => true,
            ],
            [
                'name' => 'Others',
                'description' => 'Miscellaneous fees (laboratory, library, examination, etc.)',
                'default_amount' => null,
                'is_active' => true,
            ],
        ];

        foreach ($feeTypes as $feeType) {
            FeeType::firstOrCreate(
                ['name' => $feeType['name']],
                $feeType
            );
        }
    }
}