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
            ['id' => 1, 'name' => 'Grade 7 A', 'full_name' => 'Grade 7 A (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'A', 'grade_id' => 7, 'is_active' => true],
            ['id' => 2, 'name' => 'Grade 7 B', 'full_name' => 'Grade 7 B (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'B', 'grade_id' => 7, 'is_active' => true],
            ['id' => 3, 'name' => 'Grade 7 C', 'full_name' => 'Grade 7 C (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'C', 'grade_id' => 7, 'is_active' => true],
            ['id' => 4, 'name' => 'Grade 7 D', 'full_name' => 'Grade 7 D (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'D', 'grade_id' => 7, 'is_active' => true],
            ['id' => 5, 'name' => 'Grade 7 E', 'full_name' => 'Grade 7 E (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'E', 'grade_id' => 7, 'is_active' => true],
            ['id' => 6, 'name' => 'Grade 8 A', 'full_name' => 'Grade 8 A (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'A', 'grade_id' => 8, 'is_active' => true],
            ['id' => 7, 'name' => 'Grade 8 B', 'full_name' => 'Grade 8 B (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'B', 'grade_id' => 8, 'is_active' => true],
            ['id' => 8, 'name' => 'Grade 8 C', 'full_name' => 'Grade 8 C (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'C', 'grade_id' => 8, 'is_active' => true],
            ['id' => 9, 'name' => 'Grade 8 D', 'full_name' => 'Grade 8 D (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'D', 'grade_id' => 8, 'is_active' => true],
            ['id' => 10, 'name' => 'Grade 8 E', 'full_name' => 'Grade 8 E (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'E', 'grade_id' => 8, 'is_active' => true],
            ['id' => 11, 'name' => 'Grade 9 A', 'full_name' => 'Grade 9 A (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'A', 'grade_id' => 9, 'is_active' => true],
            ['id' => 12, 'name' => 'Grade 9 B', 'full_name' => 'Grade 9 B (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'B', 'grade_id' => 9, 'is_active' => true],
            ['id' => 13, 'name' => 'Grade 9 C', 'full_name' => 'Grade 9 C (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'C', 'grade_id' => 9, 'is_active' => true],
            ['id' => 14, 'name' => 'Grade 9 D', 'full_name' => 'Grade 9 D (Afghanistan)', 'location' => 'Afghanistan', 'year' => '2024', 'level' => 'D', 'grade_id' => 8, 'is_active' => true],
        ];

        SubGrade::upsert($subGrades, ['id']);
    }
}
