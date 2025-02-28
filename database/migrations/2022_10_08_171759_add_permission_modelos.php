<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddPermissionModelos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['name' => 'modelos', 'guard_name' => 'web', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_categoria');
    }
}
