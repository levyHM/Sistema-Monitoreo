<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

// php artisan db:seed --class=RolesAndPermissionsSeeder
class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Limpiar tablas relacionadas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Definición modular
        $modulos = [
            'firmas' => ['Soluciones', 'Almacen', 'Compras', 'Proveedor', 'Reporte Faltantes', 'Reporte Soluciones','Soluciones Credito'],
            'Faltante' => ['crear', 'editar', 'visualizar', 'eliminar'],
            'Reporte Soluciones' => ['crear', 'editar', 'visualizar', 'eliminar'], // ← NUEVO
            'Reporte Faltantes' => ['crear', 'editar', 'visualizar', 'eliminar'],
            'Devoluciones' => ['crear', 'editar', 'visualizar', 'eliminar'],
            'Faltante Sobrante' => ['crear', 'editar', 'visualizar', 'eliminar'],
            'Usuarios' => ['crear', 'editar', 'visualizar', 'eliminar'],
            'dashboard' => [
                'facturas',
                'pedidos',
                'embarques',
                'embarques admin',
                'recibos',
                'Reporte faltantes',
                'usuarios'
            ],
            'sucursales' => ['cdmx', 'xalapa', 'oaxaca'],
        ];

        // Crear permisos
        $permisos = [];
        foreach ($modulos as $modulo => $acciones) {
            foreach ($acciones as $accion) {
                $permisos[] = Permission::firstOrCreate([
                    'name' => "{$modulo}.{$accion}"
                ]);
            }
        }

        // Definir roles y sus permisos actualizados
        $roles = [
            'firmas' => [
                'firmas.Soluciones',
                'firmas.Almacen',
                'firmas.Compras',
                'firmas.Proveedor',
                'firmas.Reporte Faltantes',
                'firmas.Reporte Soluciones',
                'firmas.Soluciones Credito',
            ],
            'Faltante' => [
                'Faltante.crear',
                'Faltante.editar',
                'Faltante.visualizar',
                'Faltante.eliminar',
            ],
            'Reporte Faltantes' => [
                'Reporte Faltantes.crear',
                'Reporte Faltantes.editar',
                'Reporte Faltantes.visualizar',
                'Reporte Faltantes.eliminar',
            ],
            'Reporte Soluciones' => [
                'Reporte Soluciones.crear',
                'Reporte Soluciones.editar',
                'Reporte Soluciones.visualizar',
                'Reporte Soluciones.eliminar',
            ],
            'Devoluciones' => [
                'Devoluciones.crear',
                'Devoluciones.editar',
                'Devoluciones.visualizar',
                'Devoluciones.eliminar',
            ],
            'Faltante Sobrante' => [
                'Faltante Sobrante.crear',
                'Faltante Sobrante.editar',
                'Faltante Sobrante.visualizar',
                'Faltante Sobrante.eliminar',
            ],
            'Usuarios' => [
                'Usuarios.crear',
                'Usuarios.editar',
                'Usuarios.visualizar',
                'Usuarios.eliminar',
            ],
            'Dashboard Admin' => [
                'dashboard.facturas',
                'dashboard.pedidos',
                'dashboard.embarques',
                'dashboard.embarques admin',
                'dashboard.recibos',
                'dashboard.Reporte faltantes',
                'dashboard.usuarios',
            ],
            'Sucursales' => [
                'sucursales.cdmx',
                'sucursales.xalapa',
                'sucursales.oaxaca',
            ],
        ];

        // Crear roles y asignar permisos
        foreach ($roles as $nombreRol => $permisosRol) {
            $rol = Role::firstOrCreate(['name' => $nombreRol]);
            $rol->syncPermissions($permisosRol);
        }
    }
}
