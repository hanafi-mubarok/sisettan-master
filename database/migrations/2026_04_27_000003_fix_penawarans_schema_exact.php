<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('penawarans')) {
            return;
        }

        DB::statement(
            "UPDATE penawarans p
            LEFT JOIN daftars d ON d.id = p.idfk_daftar
            LEFT JOIN users u ON u.username = d.id_daftar OR u.name = d.nama
            LEFT JOIN barang_yang_dilelang b ON b.id = p.idfk_tkd
            SET p.id_user = COALESCE(p.id_user, u.id),
                p.name = COALESCE(p.name, u.name, d.nama),
                p.idfk_barang = COALESCE(p.idfk_barang, p.idfk_tkd),
                p.aset_id = COALESCE(p.aset_id, b.aset_id, p.id_tkd)"
        );

        try {
            Schema::table('penawarans', function (Blueprint $table) {
                $table->dropForeign(['idfk_daftar']);
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('penawarans', function (Blueprint $table) {
                $table->dropForeign(['idfk_tkd']);
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('penawarans', function (Blueprint $table) {
                if (Schema::hasColumn('penawarans', 'total_luas')) {
                    $table->dropColumn('total_luas');
                }

                if (Schema::hasColumn('penawarans', 'idfk_daftar')) {
                    $table->dropColumn('idfk_daftar');
                }

                if (Schema::hasColumn('penawarans', 'id_daftar')) {
                    $table->dropColumn('id_daftar');
                }

                if (Schema::hasColumn('penawarans', 'idfk_tkd')) {
                    $table->dropColumn('idfk_tkd');
                }

                if (Schema::hasColumn('penawarans', 'id_tkd')) {
                    $table->dropColumn('id_tkd');
                }
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('penawarans', function (Blueprint $table) {
                if (Schema::hasColumn('penawarans', 'id_user')) {
                    $table->foreign('id_user')->references('id')->on('users');
                }

                if (Schema::hasColumn('penawarans', 'idfk_barang')) {
                    $table->foreign('idfk_barang')->references('id')->on('barang_yang_dilelang');
                }
            });
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('penawarans')) {
            return;
        }

        try {
            Schema::table('penawarans', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
                $table->dropForeign(['idfk_barang']);
            });
        } catch (\Throwable $e) {
        }

        Schema::table('penawarans', function (Blueprint $table) {
            if (!Schema::hasColumn('penawarans', 'total_luas')) {
                $table->integer('total_luas')->nullable()->after('id_penawaran');
            }

            if (!Schema::hasColumn('penawarans', 'idfk_daftar')) {
                $table->unsignedBigInteger('idfk_daftar')->nullable()->after('total_luas');
            }

            if (!Schema::hasColumn('penawarans', 'id_daftar')) {
                $table->string('id_daftar')->nullable()->after('idfk_daftar');
            }

            if (!Schema::hasColumn('penawarans', 'idfk_tkd')) {
                $table->unsignedBigInteger('idfk_tkd')->nullable()->after('id_daftar');
            }

            if (!Schema::hasColumn('penawarans', 'id_tkd')) {
                $table->string('id_tkd')->nullable()->after('idfk_tkd');
            }
        });

        try {
            Schema::table('penawarans', function (Blueprint $table) {
                $table->foreign('idfk_daftar')->references('id')->on('daftars');
                $table->foreign('idfk_tkd')->references('id')->on('tkds');
            });
        } catch (\Throwable $e) {
        }
    }
};