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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('written')->nullable();
            $table->unsignedDecimal('verbal')->nullable();
            $table->unsignedDecimal('attendance')->nullable();
            $table->unsignedDecimal('activity')->nullable();
            $table->unsignedDecimal('homework')->nullable();
            $table->unsignedDecimal('evaluation')->nullable();
            $table->unsignedDecimal('total')->nullable();
            $table->enum('type', [1, 2, 3])->index()->comment('1: middle, 2: final, 3:result');
            $table->boolean('is_passed')->default(false)->index();

            $table->unsignedSmallInteger('year')->index();
            $table->unsignedBigInteger('student_id');
            $table->unsignedSmallInteger('sub_grade_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedSmallInteger('subject_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unique(['year', 'student_id', 'sub_grade_id', 'subject_id', 'type'], 'student_score_unique_key');

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
        Schema::dropIfExists('scores');
    }
};
