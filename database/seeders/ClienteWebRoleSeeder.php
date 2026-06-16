<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ClienteWebRoleSeeder extends Seeder
{
    public function run()
    {
        // ponytail: firstOrCreate → idempotente, safe to re-run
        Role::firstOrCreate(['name' => 'cliente_web', 'guard_name' => 'web']);

        $this->command->info('Rol cliente_web creado (o ya existía).');
    }
}
