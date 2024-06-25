<?php

namespace Database\Seeders;

use App\Models\AttendanceMaxScore;
use Illuminate\Database\Seeder;

class AttendanceMaxScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendanceMaxScores = [
            // Attendance Max Score

            // Grade 7 A
            ['id' => 1, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 7, 'middle' => 18, 'final' => 26, 'total' => 44],
            ['id' => 2, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 4, 'middle' => 25, 'final' => 40, 'total' => 65],
            ['id' => 3, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 6, 'middle' => 13, 'final' => 18, 'total' => 31],
            ['id' => 4, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 9, 'middle' => 12, 'final' => 17, 'total' => 29],
            ['id' => 5, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 11, 'middle' => 13, 'final' => 17, 'total' => 30],
            ['id' => 6, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 5, 'middle' => 13, 'final' => 34, 'total' => 47],
            ['id' => 7, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 1, 'middle' => 25, 'final' => 36, 'total' => 61],
            ['id' => 8, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 10, 'middle' => 10, 'final' => 16, 'total' => 26],
            ['id' => 9, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 2, 'middle' => 24, 'final' => 20, 'total' => 44],
            ['id' => 10, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 8, 'middle' => 12, 'final' => 30, 'total' => 42],
            ['id' => 11, 'year' => '2023', 'sub_grade_id' => 1, 'subject_id' => 3, 'middle' => 11, 'final' => 13, 'total' => 24],

            // Grade 7 B
            ['id' => 12, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 7, 'middle' => 18, 'final' => 26, 'total' => 44],
            ['id' => 13, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 4, 'middle' => 25, 'final' => 40, 'total' => 65],
            ['id' => 14, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 6, 'middle' => 13, 'final' => 18, 'total' => 31],
            ['id' => 15, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 9, 'middle' => 12, 'final' => 17, 'total' => 29],
            ['id' => 16, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 11, 'middle' => 13, 'final' => 17, 'total' => 30],
            ['id' => 17, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 5, 'middle' => 13, 'final' => 34, 'total' => 47],
            ['id' => 18, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 1, 'middle' => 25, 'final' => 36, 'total' => 61],
            ['id' => 19, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 10, 'middle' => 10, 'final' => 16, 'total' => 26],
            ['id' => 20, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 2, 'middle' => 24, 'final' => 20, 'total' => 44],
            ['id' => 21, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 8, 'middle' => 12, 'final' => 30, 'total' => 42],
            ['id' => 22, 'year' => '2023', 'sub_grade_id' => 2, 'subject_id' => 3, 'middle' => 11, 'final' => 13, 'total' => 24],

            // Grade 8 A
            ['id' => 23, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 7, 'middle' => 18, 'final' => 26, 'total' => 44],
            ['id' => 24, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 4, 'middle' => 25, 'final' => 40, 'total' => 65],
            ['id' => 25, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 6, 'middle' => 13, 'final' => 18, 'total' => 31],
            ['id' => 26, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 9, 'middle' => 12, 'final' => 17, 'total' => 29],
            ['id' => 27, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 11, 'middle' => 13, 'final' => 17, 'total' => 30],
            ['id' => 28, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 5, 'middle' => 13, 'final' => 34, 'total' => 47],
            ['id' => 29, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 1, 'middle' => 25, 'final' => 36, 'total' => 61],
            ['id' => 30, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 10, 'middle' => 10, 'final' => 16, 'total' => 26],
            ['id' => 31, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 2, 'middle' => 24, 'final' => 20, 'total' => 44],
            ['id' => 32, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 8, 'middle' => 12, 'final' => 30, 'total' => 42],
            ['id' => 33, 'year' => '2023', 'sub_grade_id' => 3, 'subject_id' => 3, 'middle' => 11, 'final' => 13, 'total' => 24],

            // Grade 8 B
            ['id' => 34, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 7, 'middle' => 18, 'final' => 26, 'total' => 44],
            ['id' => 35, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 4, 'middle' => 25, 'final' => 40, 'total' => 65],
            ['id' => 36, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 6, 'middle' => 13, 'final' => 18, 'total' => 31],
            ['id' => 37, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 9, 'middle' => 12, 'final' => 17, 'total' => 29],
            ['id' => 38, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 11, 'middle' => 13, 'final' => 17, 'total' => 30],
            ['id' => 39, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 5, 'middle' => 13, 'final' => 34, 'total' => 47],
            ['id' => 40, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 1, 'middle' => 25, 'final' => 36, 'total' => 61],
            ['id' => 41, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 10, 'middle' => 10, 'final' => 16, 'total' => 26],
            ['id' => 42, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 2, 'middle' => 24, 'final' => 20, 'total' => 44],
            ['id' => 43, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 8, 'middle' => 12, 'final' => 30, 'total' => 42],
            ['id' => 44, 'year' => '2023', 'sub_grade_id' => 4, 'subject_id' => 3, 'middle' => 11, 'final' => 13, 'total' => 24],
        ];

        AttendanceMaxScore::upsert($attendanceMaxScores, ['id']);
    }
}
