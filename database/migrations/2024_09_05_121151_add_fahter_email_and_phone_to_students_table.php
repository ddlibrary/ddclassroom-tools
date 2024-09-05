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
        Schema::table('students', function (Blueprint $table) {
            $table->string('province')->after('phone')->nullable();
            $table->string('father_phone')->after('province')->nullable();
            $table->string('father_email')->after('father_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('province');
            $table->dropColumn('father_phone');
            $table->dropColumn('father_email');
        });
    }
};
