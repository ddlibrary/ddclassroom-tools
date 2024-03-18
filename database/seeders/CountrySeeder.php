<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            // Countries
            ['id' => 1, 'name' => 'افغانستان'],
            ['id' => 2, 'name' => 'پاکستان'],
            ['id' => 3, 'name' => 'ترکیه'],
            ['id' => 4, 'name' => 'تاجکستان'],
        ];

        Country::upsert($countries, ['id']);
    }
}
