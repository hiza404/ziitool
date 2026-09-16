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
        Schema::create('pro_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('plan')->default('monthly'); // monthly, yearly, lifetime
            $table->boolean('is_active')->default(true);
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_licenses');
    }
};
