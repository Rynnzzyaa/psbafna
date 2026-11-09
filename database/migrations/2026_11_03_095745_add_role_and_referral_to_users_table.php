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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('referrer')->after('email'); // 'admin', 'referrer'
            $table->string('referral_code')->nullable()->unique()->after('role');
            $table->string('phone')->nullable()->after('referral_code');
            $table->string('bank_info')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'referral_code', 'phone', 'bank_info']);
        });
    }
};
