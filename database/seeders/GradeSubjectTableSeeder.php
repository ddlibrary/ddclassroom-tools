<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class GradeSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = Grade::whereIsActive(true)->get();

        $subjects = Subject::whereIsActive(true)->get();

        foreach ($grades as $grade) {
            $grade->subjects()->sync($subjects);
        }
    }
}
