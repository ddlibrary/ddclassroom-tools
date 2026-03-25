<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\Enrollment;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    protected $gradeId;
    protected $year;
    protected $userId;

    public function __construct($gradeId, $year, $userId)
    {
        $this->gradeId = $gradeId;
        $this->year = $year;
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        if (!isset($row['name']) || !isset($row['moodle_id'])) {
            return [];
        }

        $name = $row['name'];
        $email = $row['email'] ?? null;
        $moodleId = $row['moodle_id'];

        if ($name === 'NULL' || $name === null) {
            return [];
        }

        $student = Student::whereIdNumber($moodleId)->first();

        // Update existing student
        if ($student) {
            info("This student already exist with moodle id: {$moodleId}");

            $student->username = $row['username'] ?? $student->username;
            $student->email = $email;
            $student->sub_grade_id = $this->gradeId;
            $student->is_active = true;
            $student->save();

            Enrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'sub_grade_id' => $student->sub_grade_id,
                    'year' => $this->year,
                ],
                [
                    'user_id' => $this->userId,
                ]
            );

            return [];
        }

        // Create new student
        $countryId = null;
        $countryName = $row['country'] ?? null;

        if ($countryName) {
            $countryId = Country::whereName($countryName)->value('id');
        }

        $student = Student::create([
            'name' => $row['name'],
            'father_name' => $row['father_name'] ?? null,
            'fa_name' => $row['fa_name'] ?? null,
            'fa_father_name' => $row['fa_father_name'] ?? null,
            'username' => $row['username'] ?? null,
            'email' => $email,
            'id_number' => $moodleId,
            'country_id' => $countryId,
            'sub_grade_id' => $this->gradeId,
            'password' => $row['password'] ?? null,
            'name_in_system' => trim(($row['name'] ?? '') . ' ' . ($row['father_name'] ?? '')),
            'is_active' => true,
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'sub_grade_id' => $student->sub_grade_id,
            'user_id' => $this->userId,
            'year' => $this->year,
        ]);

        return null;
    }
}
