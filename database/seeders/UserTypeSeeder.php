<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            // userTypes
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Teacher'],
            ['id' => 3, 'name' => 'User'],
        ];

        UserType::upsert($userTypes, ['id']);
    }
}
