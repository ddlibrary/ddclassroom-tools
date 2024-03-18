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
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedSmallInteger('sub_grade_id');
            $table->unsignedSmallInteger('total_class_hours')->nullable()->comment('مجموع ساعات درسی');
            $table->unsignedSmallInteger('present')->nullable()->comment('حاضر');
            $table->unsignedSmallInteger('absent')->nullable()->comment('غیرحاضر');
            $table->unsignedSmallInteger('permission')->nullable()->comment('رخصت');
            $table->unsignedSmallInteger('patient')->nullable()->comment('مریض');
            $table->unsignedSmallInteger('year')->index();
            $table->enum('type', [1, 2])->index()->comment('1: middle, 2:final');
            $table->string('note')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedSmallInteger('subject_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->unique(['year', 'student_id', 'sub_grade_id', 'subject_id', 'type'], 'student_attendance_detail_unique_key');

            // Foreign Keys
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('users');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('sub_grade_id')
                ->references('id')
                ->on('sub_grades');

            $table->foreign('student_id')
                ->references('id')
                ->on('students');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
