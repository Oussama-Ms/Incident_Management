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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // recipient
            $table->unsignedBigInteger('sender_id')->nullable(); // sender
            $table->unsignedBigInteger('incident_id')->nullable();
            $table->string('type', 50);
            $table->string('message', 255);
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('user')->onDelete('set null');
            $table->foreign('incident_id')->references('id')->on('incident')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
