<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class QrPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear módulo de QR si no existe
        $qrModule = Module::firstOrCreate(
            ['name' => 'qr'],
            ['description' => 'Gestión de códigos QR']
        );

        // Crear permisos para el módulo de QR
        $qrPermissions = [
            ['action' => 'view', 'permission_key' => 'qr.view'],
            ['action' => 'create', 'permission_key' => 'qr.create'],
            ['action' => 'edit', 'permission_key' => 'qr.edit'],
            ['action' => 'delete', 'permission_key' => 'qr.delete'],
        ];

        foreach ($qrPermissions as $perm) {
            Permission::firstOrCreate(
                ['permission_key' => $perm['permission_key']],
                [
                    'module_id' => $qrModule->id,
                    'action' => $perm['action'],
                ]
            );
        }

        // Asignar todos los permisos de QR al rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $qrPermissionIds = Permission::where('module_id', $qrModule->id)->pluck('id');
            $adminRole->permissions()->syncWithoutDetaching($qrPermissionIds);
        }

        // Asignar solo permiso de ver al rol user
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $viewPermission = Permission::where('permission_key', 'qr.view')->first();
            if ($viewPermission) {
                $userRole->permissions()->syncWithoutDetaching([$viewPermission->id]);
            }
        }

        $this->command->info('✅ Permisos de QR Codes creados y asignados correctamente!');
    }
}
