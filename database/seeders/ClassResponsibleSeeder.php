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
            ['id' => 1, 'year' => '2023', 'sub_grade_id' => 1, 'teacher_id' => 7],
            ['id' => 2, 'year' => '2023', 'sub_grade_id' => 2, 'teacher_id' => 3],
            ['id' => 3, 'year' => '2023', 'sub_grade_id' => 3, 'teacher_id' => 4],
            ['id' => 4, 'year' => '2023', 'sub_grade_id' => 4, 'teacher_id' => 6],
            ['id' => 5, 'year' => '2024', 'sub_grade_id' => 7, 'teacher_id' => 8],
            ['id' => 6, 'year' => '2024', 'sub_grade_id' => 8, 'teacher_id' => 10],
        ];

        ClassResponsible::upsert($classResponsible, ['id']);
    }
}
