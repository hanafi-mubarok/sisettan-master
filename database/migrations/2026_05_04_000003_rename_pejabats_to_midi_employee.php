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
        // Drop foreign key constraint for id_opd
        DB::statement('ALTER TABLE `pejabats` DROP FOREIGN KEY `pejabats_id_opd_foreign`');

        // Drop index for id_opd
        DB::statement('ALTER TABLE `pejabats` DROP INDEX `pejabats_id_opd_foreign`');

        // Drop id_opd column
        Schema::table('pejabats', function (Blueprint $table) {
            $table->dropColumn('id_opd');
        });

        // Rename columns
        DB::statement('ALTER TABLE `pejabats` CHANGE COLUMN `nama_pejabat` `nama_karyawan` VARCHAR(255) NULL DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE `pejabats` CHANGE COLUMN `nip_pejabat` `nik` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE `pejabats` CHANGE COLUMN `no_sk` `position` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');

        // Drop old unique index
        DB::statement('ALTER TABLE `pejabats` DROP INDEX `pejabats_nama_pejabat_unique`');

        // Add new unique index for nama_karyawan
        DB::statement('ALTER TABLE `pejabats` ADD UNIQUE INDEX `pejabats_nama_karyawan_unique` (`nama_karyawan`)');

        // Rename table from pejabats to midi_employee
        DB::statement('RENAME TABLE `pejabats` TO `midi_employee`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename table back from midi_employee to pejabats
        DB::statement('RENAME TABLE `midi_employee` TO `pejabats`');

        // Drop unique index for nama_karyawan
        DB::statement('ALTER TABLE `pejabats` DROP INDEX `pejabats_nama_karyawan_unique`');

        // Add back unique index for nama_pejabat
        DB::statement('ALTER TABLE `pejabats` ADD UNIQUE INDEX `pejabats_nama_pejabat_unique` (`nama_karyawan`)');

        // Rename columns back
        DB::statement('ALTER TABLE `pejabats` CHANGE COLUMN `nama_karyawan` `nama_pejabat` VARCHAR(255) NULL DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE `pejabats` CHANGE COLUMN `nik` `nip_pejabat` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE `pejabats` CHANGE COLUMN `position` `no_sk` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');

        // Add id_opd column back
        Schema::table('pejabats', function (Blueprint $table) {
            $table->unsignedBigInteger('id_opd')->after('id_jabatan');
        });

        // Add index and foreign key back for id_opd
        DB::statement('ALTER TABLE `pejabats` ADD INDEX `pejabats_id_opd_foreign` (`id_opd`)');
        DB::statement('ALTER TABLE `pejabats` ADD CONSTRAINT `pejabats_id_opd_foreign` FOREIGN KEY (`id_opd`) REFERENCES `opds` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
};
