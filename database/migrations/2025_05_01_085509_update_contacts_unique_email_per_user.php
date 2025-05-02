<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // simply add the composite unique index
            $table->unique(['user_id', 'email'], 'contacts_user_id_email_unique');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // drop that composite index
            $table->dropUnique('contacts_user_id_email_unique');
        });
    }
};