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
        Schema::create('user_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('addon_id')->constrained()->onDelete('cascade');
            $table->decimal('price_paid', 10, 2)->nullable(); // Track price paid (can be 0 for free addons)
            $table->timestamp('expires_at')->nullable(); // For trial addons
            $table->boolean('is_trial')->default(false); // Whether this is a trial purchase
            $table->string('payment_reference')->nullable(); // Reference from payment gateway
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->timestamps();

            // Prevent duplicate purchases (user can only own an addon once)
            $table->unique(['user_id', 'addon_id']);

            // Indexes for common queries
            $table->index(['user_id', 'status']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addons');
    }
};
