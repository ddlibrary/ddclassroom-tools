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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->unsignedDecimal('total_class_hours', 13, 3)->nullable()->comment('مجموع ساعات درسی');
            $table->unsignedDecimal('present', 13, 3)->nullable()->comment('حاضر');
            $table->unsignedDecimal('absent', 13, 3)->nullable()->comment('غیرحاضر');
            $table->unsignedDecimal('permission', 13, 3)->nullable()->comment('رخصت');
            $table->unsignedDecimal('patient', 13, 3)->nullable()->comment('مریض');
            $table->enum('type', [1, 2])->index()->comment('1: middle, 2:final');
            $table->unsignedSmallInteger('year')->index();
            $table->boolean('is_passed')->default(true)->index();
            $table->unsignedBigInteger('student_id');
            $table->unsignedSmallInteger('sub_grade_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->unique(['year', 'student_id', 'sub_grade_id', 'type'], 'student_attendance_unique_key');

            // Foreign Keys
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
        Schema::dropIfExists('attendances');
    }
};
