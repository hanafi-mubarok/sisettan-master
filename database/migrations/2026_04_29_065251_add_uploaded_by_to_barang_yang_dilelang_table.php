<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_yang_dilelang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang_yang_dilelang', 'uploaded_by')) {
                $table->string('uploaded_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_yang_dilelang', function (Blueprint $table) {
            if (Schema::hasColumn('barang_yang_dilelang', 'uploaded_by')) {
                $table->dropColumn('uploaded_by');
            }
        });
    }
};
