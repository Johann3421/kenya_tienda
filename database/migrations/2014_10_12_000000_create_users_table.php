<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 8);
            $table->string('nombres', 50);
            $table->string('ape_paterno', 50);
            $table->string('ape_materno', 50);
            $table->string('telefono', 20);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->char('activo', 2)->default('SI');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'dni' => '12345678',
            'nombres' => 'ADMINISTRADOR',
            'ape_paterno' => 'DEL SISTEMA',
            'ape_materno' => 'KENYA',
            'telefono' => '958021778',
            'email' => 'kenya@gmail.com',
            'username' => 'kenya',
            'password' => Hash::make('12345678'),
            'activo' => 'SI',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
