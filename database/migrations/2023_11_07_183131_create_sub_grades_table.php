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
        Schema::create('sub_grades', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('full_name')->comment('we are using this column for combining of grade name, level and location');
            $table->string('location');
            $table->unsignedInteger('year');
            $table->unsignedInteger('total_students')->default(0);
            $table->unsignedInteger('passed')->default(0);
            $table->string('link')->nullable();
            $table->string('level')->nullable();
            $table->boolean('is_active');
            $table->unsignedTinyInteger('grade_id');

            // Foreign Keys
            $table->foreign('grade_id')
                ->references('id')
                ->on('grades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_grades');
    }
};
