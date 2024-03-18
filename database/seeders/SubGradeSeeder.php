<?php

namespace Database\Seeders;

use App\Models\SubGrade;
use Illuminate\Database\Seeder;

class SubGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subGrades = [
            // Sub Grades
            ['id' => 1, 'name' => 'Grade 7 A', 'full_name' => 'Grade 7 A (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2023', 'level' => 'A', 'grade_id' => 7, 'is_active' => true],
            ['id' => 2, 'name' => 'Grade 7 B', 'full_name' => 'Grade 7 B (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2023', 'level' => 'B', 'grade_id' => 7, 'is_active' => true],
            ['id' => 3, 'name' => 'Grade 8 A', 'full_name' => 'Grade 8 A (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2023', 'level' => 'A', 'grade_id' => 8, 'is_active' => true],
            ['id' => 4, 'name' => 'Grade 8 B', 'full_name' => 'Grade 8 B (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2023', 'level' => 'B', 'grade_id' => 8, 'is_active' => true],
        ];

        SubGrade::upsert($subGrades, ['id']);
    }
}
