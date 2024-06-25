<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            // Grades
            ['id' => 1, 'name' => 'Grade 1', 'total_subjects' => 11, 'number' => 1, 'is_active' => false],
            ['id' => 2, 'name' => 'Grade 2', 'total_subjects' => 11, 'number' => 2, 'is_active' => false],
            ['id' => 3, 'name' => 'Grade 3', 'total_subjects' => 11, 'number' => 3, 'is_active' => false],
            ['id' => 4, 'name' => 'Grade 4', 'total_subjects' => 11, 'number' => 4, 'is_active' => false],
            ['id' => 5, 'name' => 'Grade 5', 'total_subjects' => 11, 'number' => 5, 'is_active' => false],
            ['id' => 6, 'name' => 'Grade 6', 'total_subjects' => 11, 'number' => 6, 'is_active' => false],
            ['id' => 7, 'name' => 'Grade 7', 'total_subjects' => 11, 'number' => 7, 'is_active' => true],
            ['id' => 8, 'name' => 'Grade 8', 'total_subjects' => 11, 'number' => 8, 'is_active' => true],
            ['id' => 9, 'name' => 'Grade 9', 'total_subjects' => 11, 'number' => 9, 'is_active' => true],
            ['id' => 10, 'name' => 'Grade 10', 'total_subjects' => 11, 'number' => 10, 'is_active' => false],
            ['id' => 11, 'name' => 'Grade 11', 'total_subjects' => 11, 'number' => 11, 'is_active' => false],
            ['id' => 12, 'name' => 'Grade 12', 'total_subjects' => 11, 'number' => 12, 'is_active' => false],
        ];

        Grade::upsert($grades, ['id']);
    }
}
