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
        Schema::create('project_credential_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('credential_id')->nullable()->constrained('project_credentials')->nullOnDelete();
            $table->string('action');
            $table->string('platform');
            $table->string('label');
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->string('recovery_email')->nullable();
            $table->string('recovery_phone')->nullable();
            $table->string('url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_credential_histories');
    }
};
