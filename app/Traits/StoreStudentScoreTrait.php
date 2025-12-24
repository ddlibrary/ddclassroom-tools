<?php

namespace App\Traits;

use App\Enums\ResultCardEnum;
use App\Enums\SubjectMinScoreEnum;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\SubGradeSubjectSemester;

trait StoreStudentScoreTrait
{
    private function storeScore($request, $score, $grade)
    {
        $student = Student::whereIdNumber($score->moodle_id)
            ->where('sub_grade_id', $request->sub_grade_id)
            ->first();

        if ($student) {

            $where = [
                'year' => $request->year,
                'student_id' => $student->id,
                'subject_id' => $request->subject_id,
                'sub_grade_id' => $request->sub_grade_id,
            ];

            $this->createStudentResult($request, $student->id, $where);

            $studentResultWhere = [
                'year' => request()->year,
                'student_id' => $student->id,
                'sub_grade_id' => $student->sub_grade_id,
            ];
            if (StudentResult::where($studentResultWhere)->doesntExist()) {
                StudentResult::create($studentResultWhere);
            }

            $type = $request->type_id;

            Score::where($where)
                ->where('type', '<>', $type == 1 ? 2 : 1)
                ->update([
                    'written' => null,
                    'verbal' => null,
                    'attendance' => null,
                    'activity' => null,
                    'homework' => null,
                    'evaluation' => null,
                    'total' => null,
                    'is_passed' => false,
                    'teacher_id' => $request->teacher_id,
                    'user_id' => auth()->id(),
                ]);

            $minAmount = $type == 1 ? SubjectMinScoreEnum::Middle->value : SubjectMinScoreEnum::Final->value;

            Score::where($where)
                ->where('type', $type)
                ->update([
                    'written' => $score->written,
                    'verbal' => $score->oral,
                    'attendance' => $score->attendance ? $score->attendance : null,
                    'activity' => $score->activity,
                    'homework' => $score->homework,
                    'evaluation' => $score->evaluation,
                    'total' => $score->total,
                    'is_passed' => $score->total >= $minAmount ? true : false,
                ]);

            //Score::where($where)->where('type', $type)->first();
            $secondScore = Score::where($where)
                ->where('type', $type == 1 ? 2 : 1)
                ->first();

            // Update final score
            Score::where($where)
                ->where('type', 3)
                ->update([
                    'written' => (float) $secondScore->written + $score->written,
                    'verbal' => (float) $secondScore->verbal + $score->oral,
                    'attendance' => (float) $secondScore->attendance + $score->attendance,
                    'activity' => (float) $secondScore->activity + $score->activity,
                    'homework' => (float) $secondScore->homework + $score->homework,
                    'evaluation' => (float) $secondScore->evaluation + $score->evaluation,
                    'total' => (float) $secondScore->total + $score->total,
                    'is_passed' => (float) $secondScore->total + $score->total >= SubjectMinScoreEnum::Success->value ? true : false,
                ]);

            $this->updateStudentResult($grade, $studentResultWhere, $type);
        }
    }

    private function createStudentResult($request, $studentId, $where)
    {
        if (Score::where($where)->doesntExist()) {
            for ($i = 1; $i < 4; $i++) {
                Score::updateOrCreate(
                    [
                        'year' => $request->year,
                        'student_id' => $studentId,
                        'subject_id' => $request->subject_id,
                        'sub_grade_id' => $request->sub_grade_id,
                        'type' => $i,
                    ],
                    [],
                );
            }
        }
    }

    private function updateStudentResult($grade, $studentResultWhere, $type)
    {
        $studentResult = StudentResult::where($studentResultWhere)->first();

        $firstTerm = Score::where($studentResultWhere)->where('type', 1)->sum('total');
        $secondTerm = Score::where($studentResultWhere)->where('type', 2)->sum('total');
        $final = $firstTerm + $secondTerm;

        // Check if this is a semester-based grade (like Grade 9)
        if ($grade->is_semester_based) {
            // Get the sub_grade_id and year from studentResultWhere
            $subGradeId = $studentResultWhere['sub_grade_id'];
            $year = $studentResultWhere['year'];
            
            // Get sub_grade-specific semester assignments for this year
            $firstSemesterSubjectIds = SubGradeSubjectSemester::where('sub_grade_id', $subGradeId)
                ->where('semester', 1)
                ->where('year', $year)
                ->pluck('subject_id')
                ->toArray();
            
            $secondSemesterSubjectIds = SubGradeSubjectSemester::where('sub_grade_id', $subGradeId)
                ->where('semester', 2)
                ->where('year', $year)
                ->pluck('subject_id')
                ->toArray();

            // Count passed subjects for first semester (middle term)
            $firstTermSubjectPassedCount = Score::where($studentResultWhere)
                ->where(['type' => 1, 'is_passed' => true])
                ->whereIn('subject_id', $firstSemesterSubjectIds)
                ->count();
            
            // Count passed subjects for second semester (final term)
            $secondTermSubjectPassedCount = Score::where($studentResultWhere)
                ->where(['type' => 2, 'is_passed' => true])
                ->whereIn('subject_id', $secondSemesterSubjectIds)
                ->count();
            
            // For final result, count all passed subjects from both semesters
            $finalSubjectPassedCount = Score::where($studentResultWhere)
                ->where(['type' => 3, 'is_passed' => true])
                ->whereIn('subject_id', array_merge($firstSemesterSubjectIds, $secondSemesterSubjectIds))
                ->count();
        } else {
            // For non-semester-based grades, use the original logic
            $firstTermSubjectPassedCount = Score::where($studentResultWhere)
                ->where(['type' => 1, 'is_passed' => true])
                ->count();
            $secondTermSubjectPassedCount = Score::where($studentResultWhere)
                ->where(['type' => 2, 'is_passed' => true])
                ->count();
            $finalSubjectPassedCount = Score::where($studentResultWhere)
                ->where(['type' => 3, 'is_passed' => true])
                ->count();
        }

        $middleMaxScore = $grade->middle_max_number;
        $finalMaxScore = $grade->final_max_number;
        $totalScores = $middleMaxScore + $finalMaxScore;

        $studentResult->update([
            'middle' => $firstTerm,
            'final' => $secondTerm,
            'total' => $final,

            'middle_percentage' => ($firstTerm * 100) / $middleMaxScore,
            'final_percentage' => ($secondTerm * 100) / $finalMaxScore,
            'percentage' => ($final * 100) / $totalScores,

            'middle_result_id' => $this->result(($firstTerm * 100) / $middleMaxScore),
            'final_result_id' => $this->result(($secondTerm * 100) / $finalMaxScore),
            'result_id' => $this->result(($final * 100) / $totalScores),

            'middle_subject_passed' => $firstTermSubjectPassedCount,
            'final_subject_passed' => $secondTermSubjectPassedCount,
            'subject_passed' => $finalSubjectPassedCount,
        ]);

        if ($type == 1) {
            $studentResult->update([
                'middle_result_name' => $this->resultStatus($grade, $studentResult, $type),
            ]);
        } else {
            $studentResult->update([
                'final_result_name' => $this->resultStatus($grade, $studentResult, $type),
                'result_name' => $this->finalResult($grade, $studentResult),
            ]);
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
        // For semester-based grades, get the total subjects for the specific semester
        if ($grade->is_semester_based) {
            $semester = $type == 1 ? 1 : 2; // type 1 = middle (first semester), type 2 = final (second semester)
            
            // Get sub_grade-specific semester assignments for this year
            $totalSubjectsForSemester = SubGradeSubjectSemester::where('sub_grade_id', $studentResult->sub_grade_id)
                ->where('semester', $semester)
                ->where('year', $studentResult->year)
                ->count();
            
            if ($type == 2) {
                if ($totalSubjectsForSemester > $studentResult->final_subject_passed + 3 || $studentResult->final_result_id == 5) {
                    return ResultCardEnum::Repeat->value;
                } elseif ($totalSubjectsForSemester > $studentResult->final_subject_passed && $studentResult->final_result_id != 5) {
                    return ResultCardEnum::TrayAgain->value;
                }
            } else {
                if ($totalSubjectsForSemester > $studentResult->middle_subject_passed || $studentResult->middle_result_id == 5) {
                    return 'ناکام';
                }
            }
        } else {
            // Original logic for non-semester-based grades
            if ($type == 2) {
                if ($grade->total_subjects > $studentResult->final_subject_passed + 3 || $studentResult->final_result_id == 5) {
                    return ResultCardEnum::Repeat->value;
                } elseif ($grade->total_subjects > $studentResult->final_subject_passed && $studentResult->final_result_id != 5) {
                    return ResultCardEnum::TrayAgain->value;
                }
            } else {
                if ($grade->total_subjects > $studentResult->middle_subject_passed || $studentResult->middle_result_id == 5) {
                    return 'ناکام';
                }
            }
        }

        return 'کامیاب';
    }

    private function finalResult($grade, $studentResult)
    {
        // For semester-based grades, get the total subjects for both semesters
        if ($grade->is_semester_based) {
            // Get sub_grade-specific semester assignments for this year
            $totalSubjectsForBothSemesters = SubGradeSubjectSemester::where('sub_grade_id', $studentResult->sub_grade_id)
                ->whereIn('semester', [1, 2])
                ->where('year', $studentResult->year)
                ->count();
            
            if ($totalSubjectsForBothSemesters > $studentResult->subject_passed + 3 || $studentResult->result_id == 5) {
                return ResultCardEnum::Repeat->value;
            } elseif ($totalSubjectsForBothSemesters > $studentResult->subject_passed && $studentResult->result_id != 5) {
                return ResultCardEnum::TrayAgain->value;
            }
        } else {
            // Original logic for non-semester-based grades
            if ($grade->total_subjects > $studentResult->subject_passed + 3 || $studentResult->result_id == 5) {
                return ResultCardEnum::Repeat->value;
            } elseif ($grade->total_subjects > $studentResult->subject_passed && $studentResult->result_id != 5) {
                return ResultCardEnum::TrayAgain->value;
            }
        }

        return 'کامیاب';
    }
}
