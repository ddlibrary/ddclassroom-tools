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
        Schema::create('grades', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name')->unique();
            $table->string('fa_name')->unique()->nullable();
            $table->unsignedInteger('number')->unique();
            $table->unsignedInteger('middle_max_number')->default(440);
            $table->unsignedInteger('final_max_number')->default(660);
            $table->unsignedSmallInteger('total_subjects')->comment('How many subjects this grade has');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
