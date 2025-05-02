<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduledAtToEmailsTable extends Migration
{
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->nullable(); // Môže byť NULL, ak sa e-mail pošle okamžite
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('scheduled_at');
        });
    }
}