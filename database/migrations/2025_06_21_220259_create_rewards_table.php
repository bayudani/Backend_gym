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
        Schema::create('rewardss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_profile_id')->constrained('member_profiles')->onDelete('cascade');
            $table->foreignId('item_reward_id')->constrained('item_rewards')->onDelete('cascade');
            $table->enum('reward_status', ['pending', 'claimed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
