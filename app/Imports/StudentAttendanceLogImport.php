<?php

namespace App\Imports;

use App\Models\AttendanceLog;
use App\Models\Student;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentAttendanceLogImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (isset($row['email'])) {
            $email = $row['email'];
            if ($email == 'NULL' || $email == null) {
                return [];
            }
            $student = Student::whereEmail($email)->first();
            if (! $student) {
                info("This student is not exist: Moodle Id $email");

                return [];
            }
            $subject = (isset($row['course_name'])) ? $row['course_name'] : null;

            $subjectId = Subject::where('en_name', 'like', substr($subject, 0, 4)."%")->value('id');
            if($subjectId){

                AttendanceLog::insert([
                    'year' => request()->year,
                    'student_id' => $student->id,
                    'month_id' => request()->month_id,
                    'subject_id' => $subjectId,
                    'sub_grade_id' => $student->sub_grade_id,
                    'status' => $row['status'],
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }else{
                info("Subject id is not found for $email, $subject");
            }

        }
    }
}
