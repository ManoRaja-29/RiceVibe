<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('body');
            $t->string('topic')->nullable()->index();
            $t->string('asker_name', 120);
            $t->string('asker_email', 190);
            $t->text('answer')->nullable();
            $t->string('status')->default('pending')->index(); // pending | published | rejected
            $t->timestamp('answered_at')->nullable();
            $t->unsignedInteger('views')->default(0);
            $t->string('ip_address', 45)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
