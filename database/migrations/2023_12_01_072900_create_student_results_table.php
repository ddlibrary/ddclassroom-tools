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
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('middle', 13, 3)->nullable()->default(0);
            $table->unsignedDecimal('final', 13, 3)->nullable()->default(0);
            $table->unsignedDecimal('total', 13, 3)->nullable()->default(0);

            $table->unsignedDecimal('middle_percentage', 13, 3)->nullable()->default(0);
            $table->unsignedDecimal('final_percentage', 13, 3)->nullable()->default(0);
            $table->unsignedDecimal('percentage', 13, 3)->nullable()->default(0);

            $table->unsignedSmallInteger('year')->index();

            $table->unsignedTinyInteger('middle_result_id')->nullable();
            $table->unsignedTinyInteger('final_result_id')->nullable();
            $table->unsignedTinyInteger('result_id')->nullable();

            $table->unsignedTinyInteger('middle_subject_passed')->default(0);
            $table->unsignedTinyInteger('final_subject_passed')->default(0);
            $table->unsignedTinyInteger('subject_passed')->default(0);

            $table->string('middle_result_name')->nullable();
            $table->string('final_result_name')->nullable();
            $table->string('result_name')->nullable();

            $table->unsignedBigInteger('student_id');
            $table->unsignedSmallInteger('sub_grade_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unique(['year', 'student_id', 'sub_grade_id'], 'student_result_unique_key');

            // Foreign Keys
            $table->foreign('teacher_id')
                ->references('id')
                ->on('users');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('middle_result_id')
                ->references('id')
                ->on('results');

            $table->foreign('final_result_id')
                ->references('id')
                ->on('results');

            $table->foreign('result_id')
                ->references('id')
                ->on('results');

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
        Schema::dropIfExists('student_results');
    }
};
