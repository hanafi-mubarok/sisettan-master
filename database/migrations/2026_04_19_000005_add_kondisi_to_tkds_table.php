<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('tkds', 'kondisi')) {
            Schema::table('tkds', function (Blueprint $table) {
                $table->string('kondisi')->nullable()->after('nama_barang');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tkds', 'kondisi')) {
            Schema::table('tkds', function (Blueprint $table) {
                $table->dropColumn('kondisi');
            });
        }
    }
};