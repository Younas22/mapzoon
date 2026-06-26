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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->renameColumn('google_analytics_id', 'google_analytics_code');
            $table->renameColumn('google_search_console', 'google_search_console_tag');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('google_analytics_code')->nullable()->change();
            $table->text('google_search_console_tag')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('google_analytics_code')->nullable()->change();
            $table->string('google_search_console_tag')->nullable()->change();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->renameColumn('google_analytics_code', 'google_analytics_id');
            $table->renameColumn('google_search_console_tag', 'google_search_console');
        });
    }
};
