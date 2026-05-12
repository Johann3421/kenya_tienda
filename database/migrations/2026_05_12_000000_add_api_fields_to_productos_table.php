<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiFieldsToProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('id');
            }
            
            if (!Schema::hasColumn('productos', 'pdf_link')) {
                $table->string('pdf_link', 500)->nullable()->after('ficha');
            }
            
            if (!Schema::hasColumn('productos', 'activo')) {
                $table->char('activo', 2)->default('SI')->comment('SI / NO')->after('pdf_link');
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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['sku', 'pdf_link', 'activo']);
        });
    }
}
