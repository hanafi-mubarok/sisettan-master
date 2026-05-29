<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $database = DB::getDatabaseName();

        // find all foreign key constraints that reference the daerahs table
        $refs = DB::select(
            "SELECT CONSTRAINT_NAME, TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = 'daerahs' AND CONSTRAINT_SCHEMA = ?",
            [$database]
        );

        foreach ($refs as $ref) {
            try {
                DB::statement(sprintf('ALTER TABLE `%s` DROP FOREIGN KEY `%s`', $ref->TABLE_NAME, $ref->CONSTRAINT_NAME));
            } catch (\Throwable $e) {
                // ignore individual failures to keep migration idempotent
            }
        }

        // finally drop the daerahs table
        if (Schema::hasTable('daerahs')) {
            Schema::drop('daerahs');
        }
    }

    public function down()
    {
        // recreate a minimal daerahs table (foreign keys are NOT re-created automatically)
        if (! Schema::hasTable('daerahs')) {
            Schema::create('daerahs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_kecamatan')->nullable();
                $table->unsignedBigInteger('id_kelurahan')->nullable();
                $table->dateTime('tanggal_lelang')->nullable();
                $table->string('noba')->nullable();
                $table->string('periode')->nullable();
                $table->unsignedBigInteger('thn_sts')->nullable();
                $table->string('surat')->nullable();
                $table->string('surat_shp')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }
};
