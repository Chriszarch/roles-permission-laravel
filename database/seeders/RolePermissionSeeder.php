<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠️ ADVERTENCIA: Este seeder eliminará todos los roles, permisos y módulos existentes
        echo "\n";
        echo "⚠️  ============================================\n";
        echo "⚠️  ADVERTENCIA: TRUNCATE DE TABLAS\n";
        echo "⚠️  ============================================\n";
        echo "⚠️  Este seeder eliminará TODOS los datos de:\n";
        echo "⚠️  - role_permissions\n";
        echo "⚠️  - user_roles\n";
        echo "⚠️  - permissions\n";
        echo "⚠️  - modules\n";
        echo "⚠️  - roles\n";
        echo "⚠️  ============================================\n";
        echo "\n";

        if (app()->environment('production')) {
            echo "❌ Este seeder NO debe ejecutarse en producción!\n";

            return;
        }
        
        //confirm input
        $handle = fopen("php://stdin", "r");
        echo "¿Estás seguro de que deseas continuar? Escribe 'SI' para confirmar: ";
        $line = fgets($handle);
        if (trim($line) !== 'SI') {
            echo "❌ Operación cancelada por el usuario.\n";
            return;
        }
        // Deshabilitar verificación de claves foráneas temporalmente
        Schema::disableForeignKeyConstraints();

        // Limpiar tablas en el orden correcto (de dependientes a independientes)
        DB::table('role_permissions')->truncate();
        DB::table('user_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('modules')->truncate();
        DB::table('roles')->truncate();

        // Reactivar verificación de claves foráneas
        Schema::enableForeignKeyConstraints();

        echo "✅ Tablas limpiadas exitosamente.\n\n";

        // Crear módulos
        $userModule = Module::create([
            'name' => 'users',
            'description' => 'Gestión de usuarios',
        ]);

        $roleModule = Module::create([
            'name' => 'roles',
            'description' => 'Gestión de roles y permisos',
        ]);

        $permissionModule = Module::create([
            'name' => 'permissions',
            'description' => 'Gestión de permisos',
        ]);

        $qrModule = Module::create([
            'name' => 'qr',
            'description' => 'Gestión de códigos QR',
        ]);

        // Crear permisos para el módulo de usuarios
        $userPermissions = [
            ['action' => 'view', 'permission_key' => 'users.view'],
            ['action' => 'create', 'permission_key' => 'users.create'],
            ['action' => 'edit', 'permission_key' => 'users.edit'],
            ['action' => 'delete', 'permission_key' => 'users.delete'],
        ];

        foreach ($userPermissions as $perm) {
            Permission::create([
                'module_id' => $userModule->id,
                'action' => $perm['action'],
                'permission_key' => $perm['permission_key'],
            ]);
        }

        // Crear permisos para el módulo de roles
        $rolePermissions = [
            ['action' => 'view', 'permission_key' => 'roles.view'],
            ['action' => 'create', 'permission_key' => 'roles.create'],
            ['action' => 'edit', 'permission_key' => 'roles.edit'],
            ['action' => 'delete', 'permission_key' => 'roles.delete'],
        ];

        foreach ($rolePermissions as $perm) {
            Permission::create([
                'module_id' => $roleModule->id,
                'action' => $perm['action'],
                'permission_key' => $perm['permission_key'],
            ]);
        }

        // Crear permisos para el módulo de permissions
        $permissionsPermissions = [
            ['action' => 'view', 'permission_key' => 'permissions.view'],
            ['action' => 'create', 'permission_key' => 'permissions.create'],
            ['action' => 'edit', 'permission_key' => 'permissions.edit'],
            ['action' => 'delete', 'permission_key' => 'permissions.delete'],
        ];

        foreach ($permissionsPermissions as $perm) {
            Permission::create([
                'module_id' => $permissionModule->id,
                'action' => $perm['action'],
                'permission_key' => $perm['permission_key'],
            ]);
        }

        // Crear permisos para el módulo de QR
        $qrPermissions = [
            ['action' => 'view', 'permission_key' => 'qr.view'],
            ['action' => 'create', 'permission_key' => 'qr.create'],
            ['action' => 'edit', 'permission_key' => 'qr.edit'],
            ['action' => 'delete', 'permission_key' => 'qr.delete'],
        ];

        foreach ($qrPermissions as $perm) {
            Permission::create([
                'module_id' => $qrModule->id,
                'action' => $perm['action'],
                'permission_key' => $perm['permission_key'],
            ]);
        }

        // Crear roles
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrador del sistema',
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'description' => 'Usuario básico',
        ]);

        // Asignar TODOS los permisos al rol admin
        $allPermissions = Permission::all();
        $adminRole->permissions()->attach($allPermissions->pluck('id'));

        // Asignar solo permisos de visualización al rol user
        $viewPermissions = Permission::where('action', 'view')->get();
        $userRole->permissions()->attach($viewPermissions->pluck('id'));

        // Crear o actualizar usuario admin y asignarle el rol
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );

        // Sincronizar rol (elimina roles existentes y asigna solo el admin)
        $adminUser->roles()->sync([$adminRole->id]);

        // Asignar rol user al usuario test existente
        $testUser = User::where('email', 'cristian.bello@ocracode.com')->first();
        if ($testUser) {
            $testUser->roles()->sync([$userRole->id]);
        }

        echo "✅ Roles y permisos creados exitosamente!\n";
        echo "👤 Admin: admin@test.com (password: password)\n";
        echo "👤 User: cristian.bello@ocracode.com (password: password)\n";
    }
}
