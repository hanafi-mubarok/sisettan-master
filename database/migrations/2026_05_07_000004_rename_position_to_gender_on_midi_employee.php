<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasColumn('midi_employee', 'position') && !Schema::hasColumn('midi_employee', 'gender')) {
            DB::statement('ALTER TABLE `midi_employee` CHANGE COLUMN `position` `gender` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('midi_employee', 'gender') && !Schema::hasColumn('midi_employee', 'position')) {
            DB::statement('ALTER TABLE `midi_employee` CHANGE COLUMN `gender` `position` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        }
    }
};
