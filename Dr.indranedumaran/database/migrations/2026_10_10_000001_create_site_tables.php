<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->string('featured_image')->nullable();
            $t->string('image_alt')->nullable();
            $t->string('video_url')->nullable();
            $t->string('author_name')->default('Dr. Indhra Nedumaran');
            $t->string('status')->default('draft')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->boolean('is_featured')->default(false);
            $t->unsignedSmallInteger('reading_time')->default(1);
            $t->unsignedInteger('views')->default(0);
            $t->string('meta_title')->nullable();
            $t->string('meta_description', 320)->nullable();
            $t->timestamps();
        });

        Schema::create('conferences', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('type')->default('conference');
            $t->string('venue')->nullable();
            $t->string('city')->nullable();
            $t->date('start_date')->index();
            $t->date('end_date')->nullable();
            $t->string('role')->nullable();
            $t->text('summary')->nullable();
            $t->longText('body')->nullable();
            $t->text('highlights')->nullable();
            $t->string('cover_image')->nullable();
            $t->json('photos')->nullable();
            $t->string('video_url')->nullable();
            $t->string('status')->default('draft')->index();
            $t->boolean('is_placeholder')->default(false);
            $t->string('meta_title')->nullable();
            $t->string('meta_description', 320)->nullable();
            $t->timestamps();
        });

        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('section')->index(); // fertility | gynaecology | maternity
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->text('summary')->nullable();
            $t->longText('body')->nullable();
            $t->string('image')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->string('meta_title')->nullable();
            $t->string('meta_description', 320)->nullable();
            $t->timestamps();
        });

        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('question');
            $t->text('answer');
            $t->string('section')->default('general');
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });

        Schema::create('pregnancy_stages', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('label')->nullable();
            $t->text('summary')->nullable();
            $t->longText('body')->nullable();
            $t->string('icon')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });

        Schema::create('profile_items', function (Blueprint $t) {
            $t->id();
            $t->string('type')->index(); // qualification | achievement | timeline | membership
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('period')->nullable();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->boolean('is_placeholder')->default(false);
            $t->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $t) {
            $t->id();
            $t->string('caption');
            $t->string('image')->nullable();
            $t->string('category')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });

        Schema::create('enquiries', function (Blueprint $t) {
            $t->id();
            $t->string('type')->default('contact');
            $t->string('name');
            $t->string('email');
            $t->string('phone', 40)->nullable();
            $t->string('subject')->nullable();
            $t->string('preferred_date')->nullable();
            $t->text('message');
            $t->string('status')->default('new')->index();
            $t->text('admin_notes')->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->timestamps();
        });

        Schema::create('media', function (Blueprint $t) {
            $t->id();
            $t->string('path');
            $t->string('thumb_path')->nullable();
            $t->string('original_name');
            $t->string('mime', 100);
            $t->unsignedBigInteger('size')->default(0);
            $t->unsignedInteger('width')->nullable();
            $t->unsignedInteger('height')->nullable();
            $t->string('alt')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['media', 'enquiries', 'gallery_items', 'profile_items', 'pregnancy_stages', 'faqs', 'services', 'conferences', 'posts', 'categories', 'settings'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
