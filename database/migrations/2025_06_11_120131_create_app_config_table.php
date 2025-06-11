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
        Schema::create('app_config', function (Blueprint $table) {
            $table->id();
            $table->string('app_version')->comment('Version of the application');
            $table->string('app_store_url')->nullable()->comment('URL for the App Store');
            $table->string('play_store_url')->nullable()->comment('URL for the Play Store');
            $table->string('email')->nullable()->comment('Contact email for the application');
            $table->string('whatsapp')->nullable()->comment('WhatsApp contact for the application');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_config');
    }
};
