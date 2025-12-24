<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sub_grade_subject_semesters')) {
            Schema::create('sub_grade_subject_semesters', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('sub_grade_id');
                $table->unsignedSmallInteger('subject_id');
                $table->unsignedTinyInteger('semester')->comment('1: First Semester, 2: Second Semester');
                
                // Foreign Keys
                $table->foreign('sub_grade_id')
                    ->references('id')
                    ->on('sub_grades')
                    ->onDelete('cascade');
                
                $table->foreign('subject_id')
                    ->references('id')
                    ->on('subjects')
                    ->onDelete('cascade');
                
                // Year column for tracking which year this assignment belongs to
                $table->string('year');
                
                // Unique constraint: each sub_grade can have a subject in a specific semester and year only once
                $table->unique(['sub_grade_id', 'subject_id', 'semester', 'year'], 'sub_grade_subject_semester_year_unique');
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_grade_subject_semesters');
    }
};
