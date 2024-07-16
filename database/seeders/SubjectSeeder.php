<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Subjects
            ['id' => 1, 'order' => 10, 'name' => 'ریاضی', 'en_name' => 'Mathematics'],
            ['id' => 2, 'order' => 20, 'name' => 'فزیک', 'en_name' => 'Physics'],
            ['id' => 3, 'order' => 30, 'name' => 'کیمیا', 'en_name' => 'Chemistry'],
            ['id' => 4, 'order' => 40, 'name' => 'بیولوژی', 'en_name' => 'Biology'],
            ['id' => 5, 'order' => 50, 'name' => 'دری', 'en_name' => 'Dari'],
            ['id' => 6, 'order' => 60, 'name' => 'پشتو', 'en_name' => 'Pashto'],
            ['id' => 7, 'order' => 70, 'name' => 'انگلیسی', 'en_name' => 'English'],
            ['id' => 8, 'order' => 80, 'name' => 'کمپیوتر', 'en_name' => 'Computer'],
            ['id' => 9, 'order' => 90, 'name' => 'تاریخ', 'en_name' => 'History'],
            ['id' => 10, 'order' => 100, 'name' => 'تعلیم وتربیه اسلامی', 'en_name' => 'Islamic'],
            ['id' => 11, 'order' => 110, 'name' => 'جغرافیه', 'en_name' => 'Geography'],
        ];

        Subject::upsert($subjects, ['id']);
    }
}
