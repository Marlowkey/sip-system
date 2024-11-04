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
            $table->dropForeign(['school_year_id']); // Adjust this if your foreign key has a different name
        });

        // Then, add the foreign key again with onDelete cascade
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('school_year_id')
                  ->references('id')->on('school_years')
                  ->onDelete('cascade'); // Add cascade on delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_year_id']);
            $table->foreign('school_year_id')
                  ->references('id')->on('school_years')
                  ->onDelete('restrict'); // or however it was originally set
        });
    }
};
