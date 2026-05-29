<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // rename table if exists
        if (Schema::hasTable('kelurahans') && ! Schema::hasTable('branch')) {
            Schema::rename('kelurahans', 'branch');
        }

        // rename column kelurahan => nama_branch in branch table
        if (Schema::hasTable('branch') && Schema::hasColumn('branch', 'kelurahan')) {
            try {
                Schema::table('branch', function (Blueprint $table) {
                    $table->renameColumn('kelurahan', 'nama_branch');
                });
            } catch (\Throwable $e) {
                // fallback for environments without doctrine/dbal
                DB::statement("ALTER TABLE `branch` CHANGE `kelurahan` `nama_branch` VARCHAR(255)");
            }
        }

        // rename id_kelurahan => id_branch in barang_yang_dilelang
        if (Schema::hasTable('barang_yang_dilelang') && Schema::hasColumn('barang_yang_dilelang', 'id_kelurahan')) {
            try {
                Schema::table('barang_yang_dilelang', function (Blueprint $table) {
                    $table->renameColumn('id_kelurahan', 'id_branch');
                });
            } catch (\Throwable $e) {
                // fallback; assume INT(11)
                DB::statement("ALTER TABLE `barang_yang_dilelang` CHANGE `id_kelurahan` `id_branch` INT(11)");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // revert column names
        if (Schema::hasTable('branch') && Schema::hasColumn('branch', 'nama_branch')) {
            try {
                Schema::table('branch', function (Blueprint $table) {
                    $table->renameColumn('nama_branch', 'kelurahan');
                });
            } catch (\Throwable $e) {
                DB::statement("ALTER TABLE `branch` CHANGE `nama_branch` `kelurahan` VARCHAR(255)");
            }
        }

        if (Schema::hasTable('barang_yang_dilelang') && Schema::hasColumn('barang_yang_dilelang', 'id_branch')) {
            try {
                Schema::table('barang_yang_dilelang', function (Blueprint $table) {
                    $table->renameColumn('id_branch', 'id_kelurahan');
                });
            } catch (\Throwable $e) {
                DB::statement("ALTER TABLE `barang_yang_dilelang` CHANGE `id_branch` `id_kelurahan` INT(11)");
            }
        }

        // rename table back
        if (Schema::hasTable('branch') && ! Schema::hasTable('kelurahans')) {
            Schema::rename('branch', 'kelurahans');
        }
    }
};
