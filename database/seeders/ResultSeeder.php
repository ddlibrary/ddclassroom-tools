<?php

namespace Database\Seeders;

use App\Models\Result;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = [
            // Results
            ['id' => 1, 'name' => 'A', 'from' => 90, 'to' => 100, 'result' => 'کامیاب'],
            ['id' => 2, 'name' => 'B', 'from' => 75, 'to' => 89, 'result' => 'کامیاب'],
            ['id' => 3, 'name' => 'C', 'from' => 60, 'to' => 74, 'result' => 'کامیاب'],
            ['id' => 4, 'name' => 'D', 'from' => 50, 'to' => 59, 'result' => 'کامیاب'],
            ['id' => 5, 'name' => 'E', 'from' => 0, 'to' => 49, 'result' => 'تکرار صنف'],
        ];

        Result::upsert($results, ['id']);
    }
}
