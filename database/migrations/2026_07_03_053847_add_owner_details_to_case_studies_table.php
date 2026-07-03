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
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('owner_role')->nullable()->after('owner_name');
            $table->string('owner_photo')->nullable()->after('owner_role');
            $table->foreignId('client_id')->nullable()->after('owner_photo')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
            $table->dropColumn(['owner_role', 'owner_photo']);
        });
    }
};
