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
        Schema::table('emails', function (Blueprint $table) {
            // Only add 'status' if it doesn't already exist
            if (!Schema::hasColumn('emails', 'status')) {
                $table->enum('status', ['odoslane', 'caka'])
                      ->default('caka')
                      ->after('recipients');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            if (Schema::hasColumn('emails', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};