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
        // Create kelurahans table if it doesn't exist
        if (!Schema::hasTable('kelurahans')) {
            Schema::create('kelurahans', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('kelurahan')->unique()->nullable();
                $table->unsignedBigInteger('id_kecamatan')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
        
        // Add sample data if table is empty
        if (DB::table('kelurahans')->count() === 0) {
            DB::table('kelurahans')->insert([
                ['id' => 1, 'kelurahan' => 'Cabang 1', 'id_kecamatan' => null],
                ['id' => 2, 'kelurahan' => 'Cabang 2', 'id_kecamatan' => null],
                ['id' => 3, 'kelurahan' => 'Cabang 3', 'id_kecamatan' => null],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelurahans');
    }
};
