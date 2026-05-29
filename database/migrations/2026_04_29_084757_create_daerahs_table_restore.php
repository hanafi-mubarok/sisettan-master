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
        Schema::create('daerahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kecamatan')->nullable();
            $table->unsignedBigInteger('id_kelurahan')->nullable();
            $table->date('tanggal_lelang')->nullable();
            $table->string('noba')->nullable();
            $table->string('periode')->nullable();
            $table->unsignedInteger('thn_sts')->nullable();
            $table->string('surat')->nullable();
            $table->string('surat_shp')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daerahs');
    }
};
