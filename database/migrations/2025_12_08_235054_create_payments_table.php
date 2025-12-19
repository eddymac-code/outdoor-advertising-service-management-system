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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->enum('method', [
                'cash',
                'cheque',
                'bank',
                'mobile'
            ])->default('cash');
            $table->tinyText('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', [
                'pending',
                'completed',
                'reversed'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
