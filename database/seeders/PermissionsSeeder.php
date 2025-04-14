<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'inicio',
            'servicio_tecnico',
            'pedidos',
            'ventas',
            'productos',
            'producto_drivers',
            'producto_drivers_ruta',
            'manual',
            'garantia',
            'clientes',
            'proveedores',
            'codigo_barras',
            'categorias',
            'modelos',
            'marcas',
            'perfiles',
            'usuarios',
            'pagina_web',
            'configuracion'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $adminRole->syncPermissions($permissions);

        $user = User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}