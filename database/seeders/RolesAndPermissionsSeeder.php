<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
//php artisan db:seed --class=RolesAndPermissionsSeeder
class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar cache de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Deshabilitar chequeo de FK para truncar tablas relacionadas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Permisos básicos
        $basicPermissions = ['crear', 'editar', 'visualizar', 'eliminar', 'firmar','cdmx','xalapa','oaxaca'];

        foreach ($basicPermissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Roles sin permisos asignados inicialmente
        $rolesWithPermissions = [
            'Recepción'  => [],
            'Soluciones' => [],
            'Almacen'    => [],
            'Compras'    => [],
            'Proveedor'  => [],
            'dashboard'  => [],
            'sucursales' => [],
        ];

        foreach ($rolesWithPermissions as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Permisos específicos para el rol 'dashboard'
        $dashboardPermisos = ['factura', 'pedidos', 'embarques', 'embarques admin', 'recibos', 'usuarios'];

        foreach ($dashboardPermisos as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $dashboardRole = Role::where('name', 'dashboard')->first();
        $dashboardRole->syncPermissions($dashboardPermisos);
    }
}
