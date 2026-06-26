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
        Schema::create('video_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('tagline')->nullable();
            $table->string('company_name')->nullable();
            $table->text('review_text')->nullable();
            $table->string('youtube_url');
            $table->string('thumbnail')->nullable();
            $table->integer('display_order')->default(0);
            $table->string('status')->default('active');
            $table->boolean('is_visible_on_homepage')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_reviews');
    }
};
