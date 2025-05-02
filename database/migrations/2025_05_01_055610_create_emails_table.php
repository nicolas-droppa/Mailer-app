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
        Schema::create('emails', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->onDelete('cascade');
            $t->string('subject');
            $t->longText('body');
            $t->json('recipients');
            $t->enum('status', ['odoslane', 'caka'])->default('caka');
            $t->timestamps();
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
