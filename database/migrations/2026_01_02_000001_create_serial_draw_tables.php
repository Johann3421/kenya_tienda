<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de números de serie registrados
        if (!Schema::hasTable('serial_numbers')) {
            Schema::create('serial_numbers', function (Blueprint $table) {
                $table->id();
                $table->string('serial')->unique();
                $table->string('owner_name')->nullable();
                $table->string('owner_email')->nullable();
                $table->string('owner_phone')->nullable();
                $table->timestamp('last_attempt_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Tabla de premios disponibles
        if (!Schema::hasTable('serial_rewards')) {
            Schema::create('serial_rewards', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedInteger('attempt_threshold')->nullable()->index();
                $table->date('available_from')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Tabla de bloqueos de dispositivos
        if (!Schema::hasTable('serial_device_locks')) {
            Schema::create('serial_device_locks', function (Blueprint $table) {
                $table->id();
                $table->string('device_hash', 128);
                $table->string('serial', 120);
                $table->string('user_agent', 512)->nullable();
                $table->string('ip_address', 64)->nullable();
                $table->timestamp('locked_at')->useCurrent();
                $table->timestamp('last_attempt_at')->nullable();
                $table->timestamps();

                $table->index(['device_hash', 'serial']);
            });
        }

        // Tabla de intentos de participación
        if (!Schema::hasTable('serial_attempts')) {
            Schema::create('serial_attempts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('producto_id');
                $table->string('serial', 120)->nullable();
                $table->string('device_id', 100)->nullable();
                $table->unsignedBigInteger('serial_reward_id')->nullable();
                $table->unsignedBigInteger('serial_device_lock_id')->nullable();
                $table->string('device_fingerprint', 128)->nullable();
                $table->string('client_ip', 64)->nullable();
                $table->string('user_agent', 512)->nullable();
                $table->unsignedInteger('attempt_number');
                $table->date('attempt_date');
                $table->timestamps();

                $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                $table->foreign('serial_reward_id')->references('id')->on('serial_rewards')->onDelete('set null');
                $table->foreign('serial_device_lock_id')->references('id')->on('serial_device_locks')->onDelete('set null');

                $table->index(['device_fingerprint', 'attempt_date']);
            });
        }

        // Tabla de reclamos de premios
        if (!Schema::hasTable('serial_reward_claims')) {
            Schema::create('serial_reward_claims', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('serial_attempt_id');
                $table->string('nombre');
                $table->string('email');
                $table->string('telefono')->nullable();
                $table->string('codigo_premio')->nullable();
                $table->timestamp('claimed_at')->nullable();
                $table->timestamps();

                $table->foreign('serial_attempt_id')->references('id')->on('serial_attempts')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serial_reward_claims');
        Schema::dropIfExists('serial_attempts');
        Schema::dropIfExists('serial_device_locks');
        Schema::dropIfExists('serial_rewards');
        Schema::dropIfExists('serial_numbers');
    }
};
