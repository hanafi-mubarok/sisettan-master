<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // rename table kecamatans -> branch if needed
        if (Schema::hasTable('kecamatans') && ! Schema::hasTable('branch')) {
            Schema::rename('kecamatans', 'branch');
        }

        // rename column kecamatan -> branch
        if (Schema::hasTable('branch') && Schema::hasColumn('branch', 'kecamatan')) {
            try {
                Schema::table('branch', function (Blueprint $table) {
                    $table->renameColumn('kecamatan', 'branch');
                });
            } catch (\Throwable $e) {
                // fallback for environments without doctrine/dbal
                DB::statement("ALTER TABLE `branch` CHANGE `kecamatan` `branch` VARCHAR(255)");
            }
        }
    }

    public function down()
    {
        // revert column
        if (Schema::hasTable('branch') && Schema::hasColumn('branch', 'branch')) {
            try {
                Schema::table('branch', function (Blueprint $table) {
                    $table->renameColumn('branch', 'kecamatan');
                });
            } catch (\Throwable $e) {
                DB::statement("ALTER TABLE `branch` CHANGE `branch` `kecamatan` VARCHAR(255)");
            }
        }

        // rename table back
        if (Schema::hasTable('branch') && ! Schema::hasTable('kecamatans')) {
            Schema::rename('branch', 'kecamatans');
        }
    }
};
