<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Mary\Traits\Toast;

class RoleSelector extends Component
{
    use Toast;

    /**
     * El rol actualmente activo en la sesión
     */
    public ?string $activeRole = null;

    /**
     * Los roles disponibles para el usuario actual
     */
    public array $availableRoles = [];

    /**
     * Inicializar el componente con el rol activo y roles disponibles
     */
    public function mount(): void
    {
        $this->activeRole = Session::get('active_role');
        $this->loadAvailableRoles();
    }

    /**
     * Cargar los roles disponibles para el usuario actual
     */
    protected function loadAvailableRoles(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->availableRoles = $user->roles()
            ->where('is_active', true)
            ->pluck('name', 'name')
            ->toArray();

        // Si no hay rol activo, establecer el primer rol disponible
        if (empty($this->activeRole) && ! empty($this->availableRoles)) {
            $this->activeRole = array_key_first($this->availableRoles);
            $this->updateActiveRole($this->activeRole);
        }
    }

    /**
     * Actualizar el rol activo del usuario
     */
    public function updateActiveRole(string $roleName): void
    {
        $user = Auth::user();
        if (! $user || ! $user->hasRole($roleName)) {
            $this->error('Error', 'Rol no válido');

            return;
        }

        // Limpiar datos antiguos de la sesión
        Session::forget(['user_roles', 'user_permissions', 'active_role']);

        // Actualizar el rol activo en la sesión
        Session::put('active_role', $roleName);
        $this->activeRole = $roleName;

        // Regenerar los permisos para el nuevo rol
        $permissions = $user->getAllPermissions($roleName);
        Session::put('user_permissions', $permissions);

        $this->success(
            title: 'Rol Actualizado',
            description: "Ahora estás actuando como: {$roleName}"
        );

        // Forzar un refresh completo para actualizar los menús y permisos
        $this->redirect(request()->header('Referer'));
    }

    /**
     * Renderizar el componente
     */
    public function render()
    {
        return view('livewire.role-selector');
    }
}
