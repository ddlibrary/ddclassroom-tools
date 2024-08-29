<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Enrollment;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (isset($row['name']) && isset($row['moodle_id'])) {

            $name = $row['name'];
            $email = $row['email'];
            $moodleId = $row['moodle_id'];
            if ($name == 'NULL' || $name == null) {
                return [];
            }
            if (Student::whereIdNumber($moodleId)->exists()) {
                info("This student already exist with moodle id: $moodleId");

                return [];
            }

            $countryId = null;

            $country = Country::whereName($row['country'])->value('id');
            if ($country) {
                $countryId = $country;
            }

            $student = Student::create([
                'name' => $row['name'],
                'father_name' => $row['father_name'],
                'fa_name' => $row['fa_name'],
                'fa_father_name' => $row['fa_father_name'],
                'username' => $row['username'],
                'email' => $email,
                'id_number' => $moodleId,
                'country_id' => $countryId,
                'sub_grade_id' => request()->grade_id,
                'password' => $row['password'],
                'name_in_system' => $row['name'].' '.$row['father_name'],
                'is_active' => true,
            ]);

            Enrollment::create([
                'student_id' => $student->id,
                'sub_grade_id' => $student->sub_grade_id,
                'user_id' => auth()->id(),
                'year' => request()->year,
            ]);
        }

    }
}
