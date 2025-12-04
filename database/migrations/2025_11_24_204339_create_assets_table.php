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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique(); // BB-001 etc.
            $table->string('type')->default('billboard');
            $table->string('location');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('size')->nullable(); // e.g. 12x8 meters
            $table->decimal('price_per_month', 12, 2);

            $table->enum('status', [
                'available',
                'on_hold',
                'pre_booked',
                'booked',
            ])->default('available');

            // When asset is on hold, expires after 3 business days
            $table->timestamp('on_hold_expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
