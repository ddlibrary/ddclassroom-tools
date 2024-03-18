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
        Schema::table('student_results', function (Blueprint $table) {
            $table->unsignedTinyInteger('result_type_id')->nullable()->after('result_id');
            $table->foreign('result_type_id')->references('id')->on('result_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_results', function (Blueprint $table) {
            $table->dropForeign(['result_type_id']);
            $table->dropColumn(['result_type_id']);
        });
    }
};
