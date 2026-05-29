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
        Schema::table('penawarans', function (Blueprint $table) {
            if (!Schema::hasColumn('penawarans', 'status_pelelang_id')) {
                $table->unsignedBigInteger('status_pelelang_id')->nullable()->after('gugur');
            }
        });

        Schema::table('penawarans', function (Blueprint $table) {
            try {
                if (Schema::hasColumn('penawarans', 'status_pelelang_id')) {
                    $table->foreign('status_pelelang_id')->references('id')->on('status_pelelangs');
                }
            } catch (\Throwable $e) {
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
        try {
            Schema::table('penawarans', function (Blueprint $table) {
                $table->dropForeign(['status_pelelang_id']);
            });
        } catch (\Throwable $e) {
        }

        Schema::table('penawarans', function (Blueprint $table) {
            if (Schema::hasColumn('penawarans', 'status_pelelang_id')) {
                $table->dropColumn('status_pelelang_id');
            }
        });
    }
};
