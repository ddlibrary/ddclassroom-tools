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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fa_name');
            $table->string('id_number')->unique()->nullable();
            $table->string('last_name')->nullable();
            $table->string('fa_last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('fa_father_name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('name_in_system')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('address')->nullable();
            $table->string('school')->nullable();
            $table->string('miradore')->nullable();
            $table->string('uuid')->unique();
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->unsignedSmallInteger('sub_grade_id')->nullable();

            // Foreign Keys
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('sub_grade_id')
                ->references('id')
                ->on('sub_grades');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
