<?php

namespace App\Imports;

use App\Enums\ResultCardEnum;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentScoreImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (isset($row['name'])) {

            $name = $row['name'];
            $moodleId = $row['moodle_id'];
            if ($moodleId == 'NULL' || $moodleId == null) {
                return [];
            }
            $student = Student::whereIdNumber($moodleId)->first();
            if (! $student) {
                info("This student is not exist:name $name");

                return [];
            }

            $where = [
                'year' => request()->year,
                'student_id' => $student->id,
                'subject_id' => request()->subject_id,
                'sub_grade_id' => $student->sub_grade_id,
            ];

            if (Score::where($where)->doesntExist()) {
                for ($i = 1; $i < 4; $i++) {
                    $score = Score::create([
                        'year' => request()->year,
                        'student_id' => $student->id,
                        'subject_id' => request()->subject_id,
                        'sub_grade_id' => $student->sub_grade_id,
                        'type' => $i,
                    ]);
                }
            }

            //$scores = Score::where($where)->get();
            $minAmount = 16;
            $type = 1;
            if ($row['exam'] == 'final') {
                $minAmount = 24;
                $type = 2;
            }
            Score::where($where)->where('type', $type)->update([
                'written' => $row['written'],
                'verbal' => $row['verbal'],
                'attendance' => isset($row['attendance']) ? $row['attendance'] : 0,
                'activity' => $row['activity'],
                'homework' => $row['homework'],
                'evaluation' => isset($row['evaluation']) ? $row['evaluation'] : 0,
                'total' => $row['total'],
                'is_passed' => $row['total'] >= $minAmount ? true : false,
            ]);

            $score = Score::where($where)->where('type', $type)->first();

            $finalScore = Score::where($where)->where('type', 3)->first();
            $finalScore->update([
                'written' => $finalScore->written + $row['written'],
                'verbal' => $finalScore->verbal + $row['verbal'],
                'attendance' => $finalScore->attendance + (isset($row['attendance']) ? $row['attendance'] : 0),
                'activity' => $finalScore->activity + $row['activity'],
                'homework' => $finalScore->homework + $row['homework'],
                'evaluation' => $finalScore->evaluation + isset($row['evaluation']) ? $row['evaluation'] : 0,
                'total' => $finalScore->total + $row['total'],
                'is_passed' => $finalScore->total + $row['total'] >= 40 ? true : false,
            ]);

            $grade = $score->subGrade->grade;
            $middleMaxScore = $grade->middle_max_number;
            $finalMaxScore = $grade->final_max_number;
            $totalScores = $middleMaxScore + $finalMaxScore;
            $amount = $score->total;
            $finalAmount = $finalScore->total;

            $studentResult = StudentResult::where([
                'year' => request()->year,
                'student_id' => $student->id,
                'sub_grade_id' => $student->sub_grade_id,
            ])->first();

            if ($studentResult) {
                $middle = $score->type == 1 ? ($studentResult->middle + $amount) : $studentResult->middle;
                $final = $score->type == 2 ? ($studentResult->final + $amount) : $studentResult->final;
                $finalAmount = $amount + $studentResult->total;

                $studentResult->update([
                    'user_id' => auth()->id(),
                    'teacher_id' => auth()->id(),

                    'middle' => $middle,
                    'final' => $final,
                    'total' => $finalAmount,

                    'middle_percentage' => $middle * 100 / $middleMaxScore,
                    'final_percentage' => $final * 100 / $finalMaxScore,
                    'percentage' => $finalAmount * 100 / $totalScores,

                    'middle_result_id' => $this->result($middle * 100 / $middleMaxScore),
                    'final_result_id' => $this->result($final * 100 / $finalMaxScore),
                    'result_id' => $this->result($finalAmount * 100 / $totalScores),

                    'middle_subject_passed' => ($score->type == 1 && $score->total >= 16) ? $studentResult->middle_subject_passed + 1 : $studentResult->middle_subject_passed,
                    'final_subject_passed' => ($score->type == 2 && $score->total >= 24) ? $studentResult->final_subject_passed + 1 : $studentResult->final_subject_passed,
                    'subject_passed' => $finalScore->total >= 40 ? $studentResult->subject_passed + 1 : $studentResult->subject_passed,
                ]);
            } else {
                $middle = $score->type == 1 ? $amount : 0;
                $final = $score->type == 2 ? $amount : 0;
                $studentResult = StudentResult::create([
                    'year' => request()->year,
                    'student_id' => $student->id,
                    'sub_grade_id' => $student->sub_grade_id,
                    'user_id' => auth()->id(),
                    'teacher_id' => auth()->id(),

                    'middle' => $middle,
                    'final' => $final,
                    'total' => $finalAmount,

                    'middle_percentage' => $middle * 100 / $middleMaxScore,
                    'final_percentage' => $final * 100 / $finalMaxScore,
                    'percentage' => $finalAmount * 100 / $totalScores,

                    'middle_result_id' => $this->result($middle * 100 / $middleMaxScore),
                    'final_result_id' => $this->result($final * 100 / $finalMaxScore),
                    'result_id' => $this->result($finalAmount * 100 / $totalScores),

                    'middle_subject_passed' => ($score->type == 1 && $score->total >= 16) ? 1 : 0,
                    'final_subject_passed' => ($score->type == 2 && $score->total >= 24) ? 1 : 0,
                    'subject_passed' => $finalScore->total >= 40 ? 1 : 0,
                ]);
            }

            if ($score->type == 1) {
                $studentResult->update([
                    'middle_result_name' => $this->resultStatus($grade, $studentResult, $score->type),
                ]);
            } else {
                $studentResult->update([
                    'final_result_name' => $this->resultStatus($grade, $studentResult, $score->type),
                    'result_name' => $this->finalResult($grade, $studentResult),
                ]);
            }
        }
    }

    private function result($percentage)
    {
        if ($percentage >= 90) {
            return 1;
        } elseif ($percentage >= 75) {
            return 2;
        } elseif ($percentage >= 60) {
            return 3;
        } elseif ($percentage >= 50) {
            return 4;
        }

        return 5;
    }

    private function resultStatus($grade, $studentResult, $type)
    {
        if ($type == 2) {
            if ($grade->total_subjects > ($studentResult->final_subject_passed + 3) || $studentResult->final_result_id == 5) {
                return ResultCardEnum::Repeat->value;
            } elseif ($grade->total_subjects > ($studentResult->final_subject_passed) && $studentResult->final_result_id != 5) {
                return ResultCardEnum::TrayAgain->value;
            }
        } else {
            if ($grade->total_subjects > $studentResult->middle_subject_passed || $studentResult->middle_result_id == 5) {
                return 'ناکام';
            }
        }

        return 'کامیاب';
    }

    private function finalResult($grade, $studentResult)
    {
        if ($grade->total_subjects > ($studentResult->subject_passed + 3) || $studentResult->result_id == 5) {
            return ResultCardEnum::Repeat->value;
        } elseif ($grade->total_subjects > ($studentResult->subject_passed) && $studentResult->result_id != 5) {
            return ResultCardEnum::TrayAgain->value;
        }

        return 'کامیاب';
    }
}
