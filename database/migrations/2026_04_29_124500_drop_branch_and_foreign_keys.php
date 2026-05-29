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

        // find foreign keys that reference the branch table
        $refs = DB::select(
            "SELECT CONSTRAINT_NAME, TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = 'branch' AND CONSTRAINT_SCHEMA = ?",
            [$database]
        );

        foreach ($refs as $ref) {
            try {
                DB::statement(sprintf('ALTER TABLE `%s` DROP FOREIGN KEY `%s`', $ref->TABLE_NAME, $ref->CONSTRAINT_NAME));
            } catch (\Throwable $e) {
                // ignore to keep migration idempotent
            }
        }

        // drop the branch table
        if (Schema::hasTable('branch')) {
            Schema::drop('branch');
        }
    }

    public function down()
    {
        // recreate a minimal branch table
        if (! Schema::hasTable('branch')) {
            Schema::create('branch', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nama_branch')->nullable();
                $table->unsignedBigInteger('id_branch')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }
};
