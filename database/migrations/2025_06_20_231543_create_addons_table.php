<?php

use App\Enums\OS;
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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained();
            $table->foreignId('software_id')->constrained();
            $table->enum('os', array_keys(OS::toArray()));
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('short_description');
            $table->text('description');
            $table->string('icon');
            $table->float('price')->nullable();
            $table->integer('trial_period')->nullable();
            $table->text('instructions')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('youtube_video_url')->nullable();
            $table->string('privacy_policy_url')->nullable();
            $table->string('terms_of_service_url')->nullable();
            $table->string('learn_more_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
