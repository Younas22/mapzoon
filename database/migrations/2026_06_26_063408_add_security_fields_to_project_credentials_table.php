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
        Schema::table('project_credentials', function (Blueprint $table) {
            $table->string('platform')->default('custom')->after('project_id');
            $table->string('recovery_email')->nullable()->after('password');
            $table->string('recovery_phone')->nullable()->after('recovery_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_credentials', function (Blueprint $table) {
            $table->dropColumn(['platform', 'recovery_email', 'recovery_phone']);
        });
    }
};
