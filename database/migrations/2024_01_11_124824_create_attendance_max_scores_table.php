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
        Schema::create('attendance_max_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('middle')->nullable()->default(0);
            $table->unsignedInteger('final')->nullable()->default(0);
            $table->unsignedInteger('total')->nullable()->default(0);
            $table->unsignedSmallInteger('sub_grade_id');
            $table->unsignedSmallInteger('subject_id');
            $table->unsignedSmallInteger('year')->index();

            // Foreign Keys
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');

            $table->foreign('sub_grade_id')
                ->references('id')
                ->on('sub_grades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_max_scores');
    }
};
