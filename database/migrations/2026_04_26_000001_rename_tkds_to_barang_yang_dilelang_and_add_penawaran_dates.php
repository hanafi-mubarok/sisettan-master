<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tkds') && !Schema::hasTable('barang_yang_dilelang')) {
            Schema::rename('tkds', 'barang_yang_dilelang');
        }

        if (Schema::hasTable('barang_yang_dilelang')) {
            Schema::table('barang_yang_dilelang', function (Blueprint $table) {
                if (!Schema::hasColumn('barang_yang_dilelang', 'tgl_start_penawaran')) {
                    $table->date('tgl_start_penawaran')->nullable()->after('foto');
                }

                if (!Schema::hasColumn('barang_yang_dilelang', 'tgl_akhir_penawaran')) {
                    $table->date('tgl_akhir_penawaran')->nullable()->after('tgl_start_penawaran');
                }
            });
        }

        if (Schema::hasTable('penawarans')) {
            try {
                Schema::table('penawarans', function (Blueprint $table) {
                    $table->dropForeign(['idfk_tkd']);
                });
            } catch (\Throwable $e) {
                // The foreign key may already be absent depending on the database state.
            }

            DB::table('penawarans')
                ->whereNotNull('idfk_tkd')
                ->whereNotIn('idfk_tkd', DB::table('barang_yang_dilelang')->select('id'))
                ->delete();

            Schema::table('penawarans', function (Blueprint $table) {
                $table->foreign('idfk_tkd')->references('id')->on('barang_yang_dilelang');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penawarans')) {
            try {
                Schema::table('penawarans', function (Blueprint $table) {
                    $table->dropForeign(['idfk_tkd']);
                });
            } catch (\Throwable $e) {
                // Ignore missing foreign key on rollback.
            }

            Schema::table('penawarans', function (Blueprint $table) {
                $table->foreign('idfk_tkd')->references('id')->on('tkds');
            });
        }

        if (Schema::hasTable('barang_yang_dilelang')) {
            Schema::table('barang_yang_dilelang', function (Blueprint $table) {
                if (Schema::hasColumn('barang_yang_dilelang', 'tgl_akhir_penawaran')) {
                    $table->dropColumn('tgl_akhir_penawaran');
                }

                if (Schema::hasColumn('barang_yang_dilelang', 'tgl_start_penawaran')) {
                    $table->dropColumn('tgl_start_penawaran');
                }
            });

            if (!Schema::hasTable('tkds')) {
                Schema::rename('barang_yang_dilelang', 'tkds');
            }
        }
    }
};