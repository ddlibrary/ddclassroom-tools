<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            // Years
            ['id' => 1, 'name' => 2022],
            ['id' => 2, 'name' => 2023],
        ];

        Year::upsert($years, ['id']);
    }
}
