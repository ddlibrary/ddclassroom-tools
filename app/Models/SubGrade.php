<?php

namespace App\Models;

use App\Models\Relations\BelongsToGrade;
use App\Models\Relations\HasManyStudentResult;
use App\Models\Relations\HasOneResponsible;
use Illuminate\Database\Eloquent\Model;

class SubGrade extends Model
{
    use BelongsToGrade, HasManyStudentResult, HasOneResponsible;

    protected $guarded = [];

    /**
     * Get all semester subject assignments for this sub_grade
     */
    public function semesterSubjects()
    {
        return $this->hasMany(SubGradeSubjectSemester::class);
    }

    /**
     * Get semester subject assignments for a specific year
     */
    public function semesterSubjectsForYear($year)
    {
        return $this->hasMany(SubGradeSubjectSemester::class)->where('year', $year);
    }

    /**
     * Get subjects for first semester
     */
    public function firstSemesterSubjects()
    {
        return $this->hasMany(SubGradeSubjectSemester::class)->where('semester', 1);
    }

    /**
     * Get subjects for first semester for a specific year
     */
    public function firstSemesterSubjectsForYear($year)
    {
        return $this->hasMany(SubGradeSubjectSemester::class)
            ->where('semester', 1)
            ->where('year', $year);
    }

    /**
     * Get subjects for second semester
     */
    public function secondSemesterSubjects()
    {
        return $this->hasMany(SubGradeSubjectSemester::class)->where('semester', 2);
    }

    /**
     * Get subjects for second semester for a specific year
     */
    public function secondSemesterSubjectsForYear($year)
    {
        return $this->hasMany(SubGradeSubjectSemester::class)
            ->where('semester', 2)
            ->where('year', $year);
    }

    /**
     * Get count of subjects for first semester (requires year to be set via scope)
     */
    public function getFirstSemesterSubjectCountAttribute()
    {
        return $this->firstSemesterSubjects()->count();
    }

    /**
     * Get count of subjects for first semester for a specific year
     */
    public function getFirstSemesterSubjectCountForYear($year)
    {
        return $this->firstSemesterSubjectsForYear($year)->count();
    }

    /**
     * Get count of subjects for second semester (requires year to be set via scope)
     */
    public function getSecondSemesterSubjectCountAttribute()
    {
        return $this->secondSemesterSubjects()->count();
    }

    /**
     * Get count of subjects for second semester for a specific year
     */
    public function getSecondSemesterSubjectCountForYear($year)
    {
        return $this->secondSemesterSubjectsForYear($year)->count();
    }

    /**
     * Get total count of subjects across both semesters (requires year to be set via scope)
     */
    public function getTotalSemesterSubjectCountAttribute()
    {
        return $this->semesterSubjects()->count();
    }

    /**
     * Get total count of subjects across both semesters for a specific year
     */
    public function getTotalSemesterSubjectCountForYear($year)
    {
        return $this->semesterSubjectsForYear($year)->count();
    }

    /**
     * Get subjects through semester assignments (many-to-many relationship)
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'sub_grade_subject_semesters')
            ->withPivot('semester', 'year')
            ->withTimestamps();
    }

    /**
     * Get subjects for a specific semester
     */
    public function subjectsForSemester($semester)
    {
        return $this->belongsToMany(Subject::class, 'sub_grade_subject_semesters')
            ->wherePivot('semester', $semester)
            ->withPivot('semester', 'year')
            ->withTimestamps();
    }

    /**
     * Get subjects for a specific semester and year
     */
    public function subjectsForSemesterAndYear($semester, $year)
    {
        return $this->belongsToMany(Subject::class, 'sub_grade_subject_semesters')
            ->wherePivot('semester', $semester)
            ->wherePivot('year', $year)
            ->withPivot('semester', 'year')
            ->withTimestamps();
    }

    /**
     * Scope to filter semester subjects by year
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereHas('semesterSubjects', function ($q) use ($year) {
            $q->where('year', $year);
        });
    }

    /**
     * Get subject IDs for first semester in a specific year
     */
    public function getFirstSemesterSubjectIdsForYear($year)
    {
        return $this->firstSemesterSubjectsForYear($year)->pluck('subject_id')->toArray();
    }

    /**
     * Get subject IDs for second semester in a specific year
     */
    public function getSecondSemesterSubjectIdsForYear($year)
    {
        return $this->secondSemesterSubjectsForYear($year)->pluck('subject_id')->toArray();
    }

    /**
     * Get all subject IDs for a specific year (both semesters)
     */
    public function getAllSemesterSubjectIdsForYear($year)
    {
        return $this->semesterSubjectsForYear($year)->pluck('subject_id')->unique()->toArray();
    }

    /**
     * Get subject IDs for a specific semester and year
     */
    public function getSubjectIdsForSemesterAndYear($semester, $year)
    {
        return $this->hasMany(SubGradeSubjectSemester::class)
            ->where('semester', $semester)
            ->where('year', $year)
            ->pluck('subject_id')
            ->toArray();
    }
}
