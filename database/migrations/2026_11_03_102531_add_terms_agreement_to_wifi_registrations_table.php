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
        Schema::table('wifi_registrations', function (Blueprint $table) {
            $table->boolean('terms_agreed')->default(false)->after('status');
            $table->unsignedSmallInteger('commitment_months')->default(18)->after('terms_agreed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wifi_registrations', function (Blueprint $table) {
            $table->dropColumn(['terms_agreed', 'commitment_months']);
        });
    }
};
