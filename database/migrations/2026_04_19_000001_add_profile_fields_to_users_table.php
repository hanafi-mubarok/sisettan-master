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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('name');
            $table->text('address')->nullable()->after('phone');
            $table->string('province')->nullable()->after('address');
            $table->string('city')->nullable()->after('province');
            $table->string('selfie_ktp_path')->nullable()->after('city');
            $table->string('kartu_keluarga_path')->nullable()->after('selfie_ktp_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'province',
                'city',
                'selfie_ktp_path',
                'kartu_keluarga_path',
            ]);
        });
    }
};
