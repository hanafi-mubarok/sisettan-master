<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('tkds', 'nama_barang')) {
            Schema::table('tkds', function (Blueprint $table) {
                $table->string('nama_barang')->nullable()->after('id_branch');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tkds', 'nama_barang')) {
            Schema::table('tkds', function (Blueprint $table) {
                $table->dropColumn('nama_barang');
            });
        }
    }
};