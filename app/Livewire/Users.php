<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Title('Usuarios')]
class Users extends Component
{
    use AuthorizesRequests, Toast;

    public $headers = [];

    public $users = [];

    public $roles = [];

    public $modules = [];

    public $permissions = [];

    public $title = 'Usuarios';

    // Variables para el modal de edición/creación
    public $showEditModal = false;

    public $showCreateModal = false;

    public $editingUser = null;

    public $isEditing = false;

    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $selectedRoles = [];

    public $selectedPermissions = [];

    public $is_active = true;

    public function mount()
    {
        $this->headers = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nombre'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'is_active', 'label' => 'Estado'],
        ];
        $this->loadUsers();
        $this->loadRoles();
        $this->loadModulesWithPermissions();
    }

    public function loadUsers()
    {
        $this->users = User::with('roles')->get();
    }

    public function loadRoles()
    {
        $this->roles = Role::where('is_active', true)->get();
    }

    public function loadModulesWithPermissions()
    {
        $this->modules = Module::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function openCreateModal()
    {
        $this->authorize('users.create', User::class);
        $this->resetForm();
        $this->showCreateModal = true;
        $this->isEditing = false;
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('users.edit', $user);

        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();

        // Obtener permisos directos del usuario a través de sus roles
        $this->selectedPermissions = $user->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->toArray();

        $this->showEditModal = true;
        $this->isEditing = true;
    }

    public function create()
    {
        try {
            $this->authorize('users.create', User::class);

            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if (empty($this->selectedRoles)) {
                $this->error('Error', 'Debe asignar al menos un rol al usuario', position: 'toast-top toast-end');

                return;
            }

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_active' => $this->is_active,
            ]);

            // Asignar roles
            $user->roles()->sync($this->selectedRoles);

            // Sincronizar permisos en los roles seleccionados
            $this->syncPermissionsToRoles();

            $this->showCreateModal = false;
            $this->loadUsers();
            $this->success('Usuario creado', 'El usuario ha sido creado correctamente', position: 'toast-top toast-end');
            $this->resetForm();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('Error de validación', 'Por favor, revisa los datos ingresados', position: 'toast-top toast-end');
            throw $e;
        } catch (\Exception $e) {
            $this->error('Error', 'No se pudo crear el usuario. Inténtalo de nuevo.', position: 'toast-top toast-end');
        }
    }

    public function save()
    {
        try {
            $this->authorize('users.edit', $this->editingUser);
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$this->editingUser->id,
            ]);

            if (empty($this->selectedRoles)) {
                $this->error('Error', 'Debe asignar al menos un rol al usuario', position: 'toast-top toast-end');

                return;
            }

            $this->editingUser->update([
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->is_active,
            ]);

            // Asignar roles
            $this->editingUser->roles()->sync($this->selectedRoles);

            // Sincronizar permisos en los roles seleccionados
            $this->syncPermissionsToRoles();

            $this->showEditModal = false;
            $this->loadUsers();
            $this->success('Usuario actualizado', 'El usuario ha sido actualizado correctamente', position: 'toast-top toast-end');
            $this->resetForm();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('Error de validación', 'Por favor, revisa los datos ingresados', position: 'toast-top toast-end');
            throw $e;
        } catch (\Exception $e) {
            $this->error('Error', 'No se pudo actualizar el usuario. Inténtalo de nuevo.', position: 'toast-top toast-end');
        }
    }

    private function syncPermissionsToRoles()
    {
        // Sincronizar permisos solo en los roles seleccionados
        foreach ($this->selectedRoles as $roleId) {
            $role = Role::find($roleId);
            if ($role) {
                // Obtener permisos actuales del rol
                $currentPermissions = $role->permissions->pluck('id')->toArray();

                // Combinar con los permisos seleccionados (sin duplicados)
                $allPermissions = array_unique(array_merge($currentPermissions, $this->selectedPermissions));

                // Sincronizar
                $role->permissions()->sync($allPermissions);
            }
        }
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'is_active',
            'editingUser',
            'selectedRoles',
            'selectedPermissions',
            'isEditing',
        ]);
        $this->is_active = true;
    }

    // ==============================================
    // MÉTODO DE "ELIMINACIÓN" (CAMBIAR ESTADO)
    // ==============================================

    public function delete($userId)
    {
        $user = User::findOrFail($userId);

        $this->authorize('users.delete', $user);
        if ($user) {
            // Cambiar el estado en lugar de eliminar
            $user->update(['is_active' => 0]);
            $this->loadUsers();
            $this->warning('Usuario desactivado', 'El usuario ha sido desactivado correctamente', position: 'toast-top toast-end');
        }
    }

    public function activate($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('users.edit', $user);

        $user->update(['is_active' => 1]);
        $this->loadUsers();
        $this->success('Usuario activado', 'El usuario ha sido activado correctamente', position: 'toast-top toast-end');
    }

    public function render()
    {
        return view('livewire.users');
    }
}
