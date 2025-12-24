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
        Schema::table('sub_grade_subject_semesters', function (Blueprint $table) {
            // Make year column not nullable and remove default
            $table->string('year')->nullable(false)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_grade_subject_semesters', function (Blueprint $table) {
            $table->string('year')->nullable()->default('2024')->change();
        });
    }
};
