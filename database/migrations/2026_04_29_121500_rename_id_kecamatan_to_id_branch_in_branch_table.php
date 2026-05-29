<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('branch') && Schema::hasColumn('branch', 'id_kecamatan')) {
            try {
                Schema::table('branch', function (Blueprint $table) {
                    $table->renameColumn('id_kecamatan', 'id_branch');
                });
            } catch (\Throwable $e) {
                // fallback SQL (assume INT)
                DB::statement("ALTER TABLE `branch` CHANGE `id_kecamatan` `id_branch` INT(11)");
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('branch') && Schema::hasColumn('branch', 'id_branch')) {
            try {
                Schema::table('branch', function (Blueprint $table) {
                    $table->renameColumn('id_branch', 'id_kecamatan');
                });
            } catch (\Throwable $e) {
                DB::statement("ALTER TABLE `branch` CHANGE `id_branch` `id_kecamatan` INT(11)");
            }
        }
    }
};
