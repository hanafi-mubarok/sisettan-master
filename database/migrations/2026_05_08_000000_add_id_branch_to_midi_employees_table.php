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
        Schema::table('midi_employee', function (Blueprint $table) {
            $table->unsignedBigInteger('id_branch')->nullable();
            $table->foreign('id_branch')->references('id')->on('branch')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('midi_employee', function (Blueprint $table) {
            $table->dropForeign(['id_branch']);
            $table->dropColumn('id_branch');
        });
    }
};
