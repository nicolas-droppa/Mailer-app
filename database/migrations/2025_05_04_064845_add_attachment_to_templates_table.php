<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('body');
        });
    }

    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('attachment_path');
        });
    }
};
