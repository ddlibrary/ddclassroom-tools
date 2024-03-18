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
            ['id' => 1, 'order' => 10, 'name' => 'ریاضی'],
            ['id' => 2, 'order' => 20, 'name' => 'فزیک'],
            ['id' => 3, 'order' => 30, 'name' => 'کیمیا'],
            ['id' => 4, 'order' => 40, 'name' => 'بیولوژی'],
            ['id' => 5, 'order' => 50, 'name' => 'دری'],
            ['id' => 6, 'order' => 60, 'name' => 'پشتو'],
            ['id' => 7, 'order' => 70, 'name' => 'انگلیسی'],
            ['id' => 8, 'order' => 80, 'name' => 'کمپیوتر'],
            ['id' => 9, 'order' => 90, 'name' => 'تاریخ'],
            ['id' => 10, 'order' => 100, 'name' => 'تعلیم وتربیه اسلامی'],
            ['id' => 11, 'order' => 110, 'name' => 'جغرافیه'],
        ];

        Subject::upsert($subjects, ['id']);
    }
    

}
