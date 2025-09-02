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
        Schema::table('projet', function (Blueprint $table) {
            $table->unsignedBigInteger('sla_id')->nullable()->after('team_id');
            $table->foreign('sla_id')->references('id')->on('sla')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projet', function (Blueprint $table) {
            $table->dropForeign(['sla_id']);
            $table->dropColumn('sla_id');
        });
    }
}; 