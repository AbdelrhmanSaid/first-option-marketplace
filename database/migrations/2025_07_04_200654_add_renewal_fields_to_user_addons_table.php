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
        Schema::table('user_addons', function (Blueprint $table) {
            // Add subscription period field
            $table->enum('subscription_period', ['one_time', 'monthly', 'quarterly', 'yearly'])->default('one_time')->after('is_trial');

            // Add billing and renewal tracking fields
            $table->timestamp('next_billing_date')->nullable()->after('subscription_period');
            $table->boolean('auto_renew')->default(true)->after('next_billing_date');
            $table->timestamp('grace_period_ends_at')->nullable()->after('auto_renew');

            // Index for billing queries
            $table->index(['next_billing_date', 'auto_renew']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addons', function (Blueprint $table) {
            $table->dropIndex(['next_billing_date', 'auto_renew']);
            $table->dropColumn(['subscription_period', 'next_billing_date', 'auto_renew', 'grace_period_ends_at']);
        });
    }
};
