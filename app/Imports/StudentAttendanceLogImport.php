<?php

namespace App\Imports;

ini_set('max_execution_time', 120);

use App\Models\AttendanceLog;
use App\Models\AttendanceMissingEmail;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentAttendanceLogImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        if (isset($row['email']) && isset($row['username']) && isset($row['status']) && isset($row['date']) && isset($row['course_name'])) {
            $username = $row['username'];
            $email = $row['username'];
            if ($username == 'NULL' || $username == null) {
                return [];
            }
            // Use cache to pre-load students and subjects
            $student = Cache::remember("student_username_{$username}", 3600, function () use ($username) {
                return Student::whereUsername($username)->first();
            });

            if (! $student) {
                AttendanceMissingEmail::updateOrCreate(['email' => $email], ['created_at' => now()]);

                return [];
            }

            if (request()->location == 'ddc') {
                $from = 0;
                $to = 3;
            } elseif (request()->location == 'dlc') {
                $from = 4;
                $to = 4;
            } elseif (request()->location == 'arsa') {
                $from = 5;
                $to = 4;
            }

            $subject = isset($row['course_name']) ? $row['course_name'] : null;
            $subjectId = Cache::remember('subject_name_'.substr($subject, $from, $to), 3600, function () use ($subject, $from, $to) {
                return Subject::where('en_name', 'like', substr($subject, $from, $to).'%')->value('id');
            });

            if ($subjectId) {
                $createdAt = now();
                if (isset($row['date'])) {
                    $dateValue = $row['date'];
                    if (is_numeric($dateValue)) {
                        $dateTime = Carbon::instance(Date::excelToDateTimeObject($dateValue));
                        $createdAt = $dateTime->format('Y-m-d H:i:s');
                    } else {
                        try {
                            $dateTime = Carbon::parse($dateValue);
                            $createdAt = $dateTime->format('Y-m-d H:i:s');
                        } catch (\Exception $e) {
                        }
                    }
                }

                AttendanceLog::insert([
                    'year' => request()->year,
                    'student_id' => $student->id,
                    'month_id' => request()->month_id,
                    'subject_id' => $subjectId,
                    'sub_grade_id' => $student->sub_grade_id,
                    'status' => $row['status'],
                    'first_term' => request()->term == 1 ? true : false,
                    'user_id' => auth()->id(),
                    'date' => $createdAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                info("Subject id is not found for $email, $subject");
            }
        }
    }
}
