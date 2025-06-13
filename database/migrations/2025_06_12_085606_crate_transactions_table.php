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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->foreignId('membership_package_id')->constrained('memberships')->onDelete('cascade');

            $table->integer('amount');
            // $table->enum('payment_method', ['manual_transfer'])->default('manual_transfer');
            $table->string('proof_image'); // simpan path bukti tf
            $table->enum('status', ['pending', 'Confirmed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};