<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();

            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('blog_categories')
                  ->nullOnDelete();

            $table->string('image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // TYPOGRAPHY
            $table->string('direction', 5)->default('ltr');
            $table->string('text_align', 20)->default('left');
            $table->string('line_height', 10)->default('1.6');

            // MEDIA
            $table->string('caption')->nullable();
            $table->json('gallery')->nullable();

            // META EXTRA
            $table->text('keywords')->nullable();
            $table->string('author_name')->nullable();
            $table->boolean('show_author')->default(true);

            // PUBLISH
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};