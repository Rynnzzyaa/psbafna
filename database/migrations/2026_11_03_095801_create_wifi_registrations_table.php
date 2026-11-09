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
        Schema::create('wifi_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('wifi_package_id')->constrained('wifi_packages')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('customer_nik')->nullable();
            $table->text('installation_address');
            $table->string('city')->default('Jakarta');
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->date('preferred_installation_date')->nullable();
            $table->text('customer_notes')->nullable();
            $table->string('referral_code_used')->nullable()->index();
            $table->string('status')->default('pending')->index(); // pending, survey, scheduled, installed, rejected
            $table->text('admin_notes')->nullable();
            $table->unsignedInteger('commission_amount')->default(0);
            $table->string('commission_status')->default('unpaid')->index(); // unpaid, paid, cancelled
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_registrations');
    }
};
