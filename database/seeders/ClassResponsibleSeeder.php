<?php

namespace Database\Seeders;

use App\Models\ClassResponsible;
use Illuminate\Database\Seeder;

class ClassResponsibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classResponsible = [
            // Class Responsible
            ['id' => 1, 'year' => '2024', 'sub_grade_id' => 1, 'teacher_id' => 9],
            ['id' => 2, 'year' => '2024', 'sub_grade_id' => 2, 'teacher_id' => 13],
            ['id' => 3, 'year' => '2024', 'sub_grade_id' => 3, 'teacher_id' => 15],
            ['id' => 4, 'year' => '2024', 'sub_grade_id' => 4, 'teacher_id' => 16],
            ['id' => 5, 'year' => '2024', 'sub_grade_id' => 5, 'teacher_id' => 16],
            ['id' => 6, 'year' => '2024', 'sub_grade_id' => 6, 'teacher_id' => 7],
            ['id' => 7, 'year' => '2024', 'sub_grade_id' => 7, 'teacher_id' => 3],
            ['id' => 8, 'year' => '2024', 'sub_grade_id' => 8, 'teacher_id' => 8],
            ['id' => 9, 'year' => '2024', 'sub_grade_id' => 9, 'teacher_id' => 10],
            ['id' => 10, 'year' => '2024', 'sub_grade_id' => 10, 'teacher_id' => 9],

            ['id' => 11, 'year' => '2024', 'sub_grade_id' => 11, 'teacher_id' => 14],
            ['id' => 12, 'year' => '2024', 'sub_grade_id' => 12, 'teacher_id' => 6],
            ['id' => 13, 'year' => '2024', 'sub_grade_id' => 13, 'teacher_id' => 11],
            ['id' => 14, 'year' => '2024', 'sub_grade_id' => 14, 'teacher_id' => 13],
        ];

        ClassResponsible::upsert($classResponsible, ['id']);
    }
}
