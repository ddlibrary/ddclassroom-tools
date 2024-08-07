<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentAttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (isset($row['moodle_id'])) {
            $moodleId = $row['moodle_id'];
            if ($moodleId == 'NULL' || $moodleId == null) {
                return [];
            }
            $student = Student::whereIdNumber($moodleId)->first();
            if (! $student) {
                info("This student is not exist: Moodle Id $moodleId");

                return [];
            }

            $where = [
                'year' => request()->year,
                'student_id' => $student->id,
                'sub_grade_id' => $student->sub_grade_id,
            ];

            $type = request()->type;

            $details = AttendanceDetail::create([
                'type' => $type,
                'year' => request()->year,
                'student_id' => $student->id,
                'subject_id' => request()->subject_id,
                'sub_grade_id' => $student->sub_grade_id,
                'teacher_id' => request()->teacher_id,
                'user_id' => auth()->id(),
                'total_class_hours' => isset($row['total_class_hours']) ? $row['total_class_hours'] : 0,
                'present' => isset($row['present']) ? $row['present'] : 0,
                'absent' => isset($row['absent']) ? $row['absent'] : 0,
                'permission' => isset($row['permission']) ? $row['permission'] : 0,
                'patient' => 0,
            ]);

            $attendance = Attendance::where($where)
                ->where('type', $type)
                ->first();
            if ($attendance) {

                $attendance->update([
                    'total_class_hours' => $attendance->total_class_hours + $details->total_class_hours,
                    'present' => $attendance->present + $details->present,
                    'absent' => $attendance->absent + $details->absent,
                    'permission' => $attendance->permission + $details->permission,
                    'patient' => $attendance->patient + $details->patient,
                ]);

            } else {
                Attendance::create([
                    'type' => $type,
                    'year' => request()->year,
                    'student_id' => $student->id,
                    'sub_grade_id' => $student->sub_grade_id,
                    'teacher_id' => request()->teacher_id,
                    'user_id' => auth()->id(),
                    'total_class_hours' => isset($row['total_class_hours']) ? $row['total_class_hours'] : 0,
                    'present' => isset($row['present']) ? $row['present'] : 0,
                    'absent' => isset($row['absent']) ? $row['absent'] : 0,
                    'permission' => isset($row['permission']) ? $row['permission'] : 0,
                    'patient' => 0, //isset($row['patient']) ? $row['patient'] : 0,
                ]);

            }
        }
    }
}
