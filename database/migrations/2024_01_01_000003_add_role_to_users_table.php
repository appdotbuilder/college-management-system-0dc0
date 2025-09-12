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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'staff', 'student'])->default('student');
            $table->string('nric_no')->nullable();
            $table->date('intake_date')->nullable();
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn(['role', 'nric_no', 'intake_date', 'program_id']);
        });
    }
};