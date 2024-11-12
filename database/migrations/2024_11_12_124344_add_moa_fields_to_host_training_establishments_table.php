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
        Schema::table('host_training_establishments', function (Blueprint $table) {
            $table->string('moa_file_path')->nullable()->after('email'); // Add column for MOA file path
            $table->date('moa_validity_period')->nullable()->after('moa_file_path'); // Add column for MOA validity period
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('host_training_establishments', function (Blueprint $table) {
            $table->dropColumn('moa_file_path');
            $table->dropColumn('moa_validity_period');
        });
    }
};
