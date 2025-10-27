<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear módulos
        $userModule = Module::create([
            'name' => 'users',
            'description' => 'Gestión de usuarios',
        ]);

        $roleModule = Module::create([
            'name' => 'roles',
            'description' => 'Gestión de roles y permisos',
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

        // Crear usuario admin y asignarle el rol
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $adminUser->roles()->attach($adminRole->id);

        // Asignar rol user al usuario test existente
        $testUser = User::where('email', 'cristian.bello@ocracode.com')->first();
        if ($testUser) {
            $testUser->roles()->attach($userRole->id);
        }

        echo "✅ Roles y permisos creados exitosamente!\n";
        echo "👤 Admin: admin@test.com (password: password)\n";
        echo "👤 User: cristian.bello@ocracode.com (password: password)\n";
    }
}
