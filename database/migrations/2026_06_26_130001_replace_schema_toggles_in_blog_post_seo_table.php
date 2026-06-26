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
        Schema::table('blog_post_seo', function (Blueprint $table) {
            $table->dropColumn(['enable_article_schema', 'enable_breadcrumb_schema', 'enable_faq_schema']);
            $table->longText('custom_schema')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_post_seo', function (Blueprint $table) {
            $table->dropColumn('custom_schema');
            $table->boolean('enable_article_schema')->default(true);
            $table->boolean('enable_breadcrumb_schema')->default(true);
            $table->boolean('enable_faq_schema')->default(false);
        });
    }
};
