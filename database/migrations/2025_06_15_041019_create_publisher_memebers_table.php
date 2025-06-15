<?php

use App\Enums\PublisherMemberRole;
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
        Schema::create('publisher_memebers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('publishers');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('role', array_keys(PublisherMemberRole::toArray()))->default(PublisherMemberRole::Member->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_memebers');
    }
};
