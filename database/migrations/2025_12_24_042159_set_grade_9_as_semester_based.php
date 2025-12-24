<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set Grade 9 as semester-based
        DB::table('grades')
            ->where('id', 9)
            ->update(['is_semester_based' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert Grade 9 to non-semester-based
        DB::table('grades')
            ->where('id', 9)
            ->update(['is_semester_based' => false]);
    }
};
