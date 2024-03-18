<?php

namespace Database\Seeders;

use App\Models\ResultType;
use Illuminate\Database\Seeder;

class ResultTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resultTypes = [
            // Result Types

            ['id' => 1, 'name' => 'کامیاب', 'exam' => 'midterm'],
            ['id' => 2, 'name' => 'ناکام', 'exam' => 'midterm'],
            ['id' => 3, 'name' => 'کامیاب', 'exam' => 'final'],
            ['id' => 4, 'name' => 'مشروط', 'exam' => 'final'],
            ['id' => 5, 'name' => 'ناکام', 'exam' => 'final'],
        ];

        ResultType::upsert($resultTypes, ['id']);
    }
}
