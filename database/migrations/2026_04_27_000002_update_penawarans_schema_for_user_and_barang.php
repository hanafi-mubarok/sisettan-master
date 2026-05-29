<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('penawarans')) {
            return;
        }

        Schema::table('penawarans', function (Blueprint $table) {
            if (!Schema::hasColumn('penawarans', 'id_user')) {
                $table->unsignedBigInteger('id_user')->nullable()->after('id_penawaran');
            }

            if (!Schema::hasColumn('penawarans', 'name')) {
                $table->string('name')->nullable()->after('id_user');
            }

            if (!Schema::hasColumn('penawarans', 'idfk_barang')) {
                $table->unsignedBigInteger('idfk_barang')->nullable()->after('name');
            }

            if (!Schema::hasColumn('penawarans', 'aset_id')) {
                $table->string('aset_id')->nullable()->after('idfk_barang');
            }
        });

        Schema::table('penawarans', function (Blueprint $table) {
            try {
                if (Schema::hasColumn('penawarans', 'id_user')) {
                    $table->foreign('id_user')->references('id')->on('users');
                }
            } catch (\Throwable $e) {
            }

            try {
                if (Schema::hasColumn('penawarans', 'idfk_barang')) {
                    $table->foreign('idfk_barang')->references('id')->on('barang_yang_dilelang');
                }
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('penawarans')) {
            return;
        }

        Schema::table('penawarans', function (Blueprint $table) {
            try {
                $table->dropForeign(['idfk_barang']);
            } catch (\Throwable $e) {
            }

            try {
                $table->dropForeign(['id_user']);
            } catch (\Throwable $e) {
            }
        });

        Schema::table('penawarans', function (Blueprint $table) {
            if (Schema::hasColumn('penawarans', 'aset_id')) {
                $table->dropColumn('aset_id');
            }

            if (Schema::hasColumn('penawarans', 'idfk_barang')) {
                $table->dropColumn('idfk_barang');
            }

            if (Schema::hasColumn('penawarans', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('penawarans', 'id_user')) {
                $table->dropColumn('id_user');
            }
        });
    }
};