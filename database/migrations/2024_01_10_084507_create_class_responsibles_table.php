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
        Schema::create('class_responsibles', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sub_grade_id');
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedBigInteger('teacher_id')->nullable();

            $table->unique(['year', 'teacher_id', 'sub_grade_id'], 'class_responsible_unique_key');

            // Foreign Keys

            $table->foreign('teacher_id')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('class_responsibles');
    }
};
