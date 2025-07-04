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
        Schema::table('addons', function (Blueprint $table) {
            // Add subscription type field
            $table->enum('subscription_type', ['one_time', 'subscription'])->default('one_time')->after('price');

            // Add subscription pricing fields
            $table->decimal('monthly_price', 10, 2)->nullable()->after('subscription_type');
            $table->decimal('quarterly_price', 10, 2)->nullable()->after('monthly_price');
            $table->decimal('yearly_price', 10, 2)->nullable()->after('quarterly_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->dropColumn(['subscription_type', 'monthly_price', 'quarterly_price', 'yearly_price']);
        });
    }
};
