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
        Schema::table('incident', function (Blueprint $table) {
            $table->string('priority', 20)->default('Normal')->after('description');
            $table->string('category', 50)->nullable()->after('priority');
            $table->dateTime('due_date')->nullable()->after('category');
            $table->string('contact_phone', 30)->nullable()->after('due_date');
            $table->string('location', 100)->nullable()->after('contact_phone');
            $table->text('notes')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident', function (Blueprint $table) {
            $table->dropColumn(['priority', 'category', 'due_date', 'contact_phone', 'location', 'notes']);
        });
    }
};
