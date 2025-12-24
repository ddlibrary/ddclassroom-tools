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
        // Add year column if it doesn't exist
        if (!Schema::hasColumn('sub_grade_subject_semesters', 'year')) {
            Schema::table('sub_grade_subject_semesters', function (Blueprint $table) {
                $table->string('year')->after('semester')->default('2024');
            });
        }

        // Update existing records to have a default year (get from sub_grade or use current year)
        DB::statement("
            UPDATE sub_grade_subject_semesters sgs
            INNER JOIN sub_grades sg ON sgs.sub_grade_id = sg.id
            SET sgs.year = sg.year
            WHERE sgs.year = '' OR sgs.year IS NULL
        ");

        // Check if old unique constraint exists and drop it
        $indexes = DB::select("SHOW INDEX FROM sub_grade_subject_semesters WHERE Key_name = 'sub_grade_subject_semester_unique'");
        if (!empty($indexes)) {
            // First, we need to check if there are any foreign keys using this index
            // If not, we can drop it safely
            try {
                DB::statement('ALTER TABLE sub_grade_subject_semesters DROP INDEX sub_grade_subject_semester_unique');
            } catch (\Exception $e) {
                // If it fails, the index might be used elsewhere, skip it
            }
        }

        // Create new unique constraint including year if it doesn't exist
        $newIndexes = DB::select("SHOW INDEX FROM sub_grade_subject_semesters WHERE Key_name = 'sub_grade_subject_semester_year_unique'");
        if (empty($newIndexes)) {
            Schema::table('sub_grade_subject_semesters', function (Blueprint $table) {
                $table->unique(['sub_grade_id', 'subject_id', 'semester', 'year'], 'sub_grade_subject_semester_year_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_grade_subject_semesters', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('sub_grade_subject_semester_year_unique');
            
            // Remove year column
            $table->dropColumn('year');
            
            // Restore old unique constraint
            $table->unique(['sub_grade_id', 'subject_id', 'semester'], 'sub_grade_subject_semester_unique');
        });
    }
};
