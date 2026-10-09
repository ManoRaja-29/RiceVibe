<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $t) {
            $t->id();
            $t->string('author_name', 120);
            $t->string('visit_for')->nullable();
            $t->text('body');
            $t->string('source')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
