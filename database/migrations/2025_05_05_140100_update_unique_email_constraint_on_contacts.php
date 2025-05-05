<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUniqueEmailConstraintOnContacts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // 1) odstránenie cudzí kľúč
            $table->dropForeign('contacts_ibfk_1');

            // 2) odstránenie unikátneho indexu na 'user_id' a 'email'
            $table->dropUnique('contacts_user_id_email_unique');
            
            // 3) pridanie nového unikátneho indexu na kombináciu 'user_id' a 'email'
            $table->unique(['user_id', 'email'], 'contacts_user_id_email_unique');
            
            // 4) znovu pridanie cudzí kľúč
            $table->foreign('user_id', 'contacts_ibfk_1')
                ->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // 1) odstránenie nového unikátneho indexu na 'user_id' a 'email'
            $table->dropUnique('contacts_user_id_email_unique');
            
            // 2) pridanie pôvodného unikátneho indexu na 'email'
            $table->unique('email');
            
            // 3) znovu pridanie cudzí kľúč
            $table->foreign('user_id', 'contacts_ibfk_1')
                ->references('id')->on('users')->onDelete('cascade');
        });
    }
}
