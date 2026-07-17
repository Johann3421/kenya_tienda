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
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'sonido')) {
                $table->string('sonido')->nullable();
            }
            if (!Schema::hasColumn('productos', 'chipset')) {
                $table->string('chipset')->nullable();
            }
            if (!Schema::hasColumn('productos', 'slot_expansion')) {
                $table->string('slot_expansion')->nullable();
            }
            if (!Schema::hasColumn('productos', 'fuente_poder')) {
                $table->string('fuente_poder')->nullable();
            }
            if (!Schema::hasColumn('productos', 'accesorios')) {
                $table->text('accesorios')->nullable();
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
            $table->dropColumn([
                'sonido',
                'chipset',
                'slot_expansion',
                'fuente_poder',
                'accesorios'
            ]);
        });
    }
};
