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
        Schema::create('wifi_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('speed_mbps');
            $table->unsignedInteger('price');
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->unsignedInteger('commission_reward')->default(50000); // Komisi pembawa pelanggan
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_packages');
    }
};
