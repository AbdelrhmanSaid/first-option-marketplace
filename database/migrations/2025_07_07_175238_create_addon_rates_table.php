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
        Schema::create('addon_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id')->constrained('addons');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('rate');
            $table->text('comment')->nullable();
            $table->text('reply')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_rates');
    }
};
