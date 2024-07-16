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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedSmallInteger('sub_grade_id');
            $table->string('status', 4)->comment('P: present, A: Absent, L: Late, E:execude');
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedSmallInteger('subject_id');
            $table->unsignedTinyInteger('month_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');

            $table->foreign('month_id')
                ->references('id')
                ->on('months');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('sub_grade_id')
                ->references('id')
                ->on('sub_grades');

            $table->foreign('student_id')
                ->references('id')
                ->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');

    }
};
