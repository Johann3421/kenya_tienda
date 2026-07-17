<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users_precios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dni', 12)->nullable();
            $table->string('nombres', 100);
            $table->string('ape_paterno', 50)->nullable();
            $table->string('ape_materno', 50)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('activo', ['SI', 'NO'])->default('SI');
            $table->rememberToken();
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
        Schema::dropIfExists('users_precios');
    }
};
