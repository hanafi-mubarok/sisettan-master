<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        try {
            Schema::table('tkds', function ($table) {
                $table->dropForeign(['id_kelurahan']);
            });
        } catch (\Throwable $e) {
            // FK may not exist in some environments.
        }

        DB::statement('ALTER TABLE tkds RENAME COLUMN id_tkd TO aset_id');
        DB::statement('ALTER TABLE tkds RENAME COLUMN id_kelurahan TO id_branch');
        DB::statement('ALTER TABLE tkds RENAME COLUMN bidang TO kategori');
        DB::statement('ALTER TABLE tkds RENAME COLUMN letak TO merk');
        DB::statement('ALTER TABLE tkds RENAME COLUMN bukti TO lokasi');
        DB::statement('ALTER TABLE tkds RENAME COLUMN luas TO kelipatan');
        DB::statement('ALTER TABLE tkds RENAME COLUMN longitude TO status');

        Schema::table('tkds', function ($table) {
            $table->integer('tahun')->nullable()->after('kelipatan');
        });

        DB::statement('ALTER TABLE tkds MODIFY harga_dasar BIGINT NULL');
        DB::statement('ALTER TABLE tkds MODIFY kelipatan INT NULL');
        DB::statement('ALTER TABLE tkds MODIFY status VARCHAR(191) NULL');

        DB::statement("UPDATE tkds SET harga_dasar = NULLIF(REPLACE(REPLACE(REPLACE(harga_dasar, '.', ''), ',', ''), ' ', ''), '')");
        DB::statement("UPDATE tkds SET kelipatan = NULLIF(REPLACE(REPLACE(REPLACE(kelipatan, '.', ''), ',', ''), ' ', ''), '')");
        DB::statement("UPDATE tkds SET status = COALESCE(NULLIF(status, ''), NULLIF(nop, ''))");

        Schema::table('tkds', function ($table) {
            $table->dropColumn('latitude');
            $table->dropColumn('nop');
        });
    }

    public function down(): void
    {
        Schema::table('tkds', function ($table) {
            $table->string('latitude')->nullable()->after('status');
            $table->string('nop')->nullable()->after('keterangan');
        });

        DB::statement('ALTER TABLE tkds RENAME COLUMN aset_id TO id_tkd');
        DB::statement('ALTER TABLE tkds RENAME COLUMN id_branch TO id_kelurahan');
        DB::statement('ALTER TABLE tkds RENAME COLUMN kategori TO bidang');
        DB::statement('ALTER TABLE tkds RENAME COLUMN merk TO letak');
        DB::statement('ALTER TABLE tkds RENAME COLUMN lokasi TO bukti');
        DB::statement('ALTER TABLE tkds RENAME COLUMN kelipatan TO luas');
        DB::statement('ALTER TABLE tkds RENAME COLUMN status TO longitude');

        Schema::table('tkds', function ($table) {
            $table->dropColumn('tahun');
        });

        DB::statement('ALTER TABLE tkds MODIFY harga_dasar VARCHAR(191) NULL');
        DB::statement('ALTER TABLE tkds MODIFY luas VARCHAR(191) NULL');
    }
};
