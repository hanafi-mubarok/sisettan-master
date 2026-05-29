<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_name')->nullable()->after('name');
        });

        DB::statement("UPDATE users u
            LEFT JOIN model_has_roles mhr
                ON mhr.model_id = u.id
                AND mhr.model_type = 'App\\\\Models\\\\User'
            LEFT JOIN roles r ON r.id = mhr.role_id
            SET u.role_name = COALESCE(r.name, 'user')
            WHERE u.role_name IS NULL");
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_name');
        });
    }
};
