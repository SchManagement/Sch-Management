<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->longText('business_problem')->nullable();
            $table->longText('solution')->nullable();
            $table->json('features')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('development_process')->nullable();
            $table->json('suitable_for')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 32)->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 2048)->nullable();
            $table->string('primary_target_keyword')->nullable();
            $table->text('secondary_keyword_notes')->nullable();
            $table->boolean('is_indexable')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index(['status', 'sort_order']);
            $table->index(['is_indexable', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
