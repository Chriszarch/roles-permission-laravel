<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('Usuarios')]
class Users extends Component
{
    use AuthorizesRequests, Toast, WithPagination;

    public $headers = [];


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
            ['key' => 'id', 'label' => 'ID', 'class' => 'w-10'],
            ['key' => 'name', 'label' => 'Nombre', 'class' => 'w-20'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-20'],
            ['key' => 'is_active', 'label' => 'Estado', 'class' => 'w-24'],
        ];
        $this->loadRoles();
        $this->loadModulesWithPermissions();
    }

    public function loadUsers()
    {
        $query = User::with('roles');

        // Si el usuario actual es admin, permitirle ver su propio usuario
        if (Auth::user()->hasRole('admin')) {
            $query->where(function($q) {
                $q->whereDoesntHave('roles', function($q2) {
                    $q2->where('name', 'admin');
                })
                ->orWhere('id', Auth::id()); // Incluir el propio usuario admin
            });
        } else {
            // Para usuarios no-admin, excluir todos los admin
            $query->whereDoesntHave('roles', function($q) {
                $q->where('name', 'admin');
            });
        }

        return $query->paginate(5)->through(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'roles' => $user->roles->pluck('name')->toArray(),
        ]);
    }

    public function loadRoles()
    {
        $query = Role::where('is_active', true);
        
        // Si estamos editando y NO es el propio perfil del admin, ocultar rol admin
        if (!$this->editingUser || ($this->editingUser && !$this->editingUser->hasRole('admin'))) {
            $query->where('name', '!=', 'admin');
        }
        
        $this->roles = $query->get();
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

        // Verificar si el usuario a editar tiene rol admin
        $isTargetAdmin = $user->hasRole('admin');
        
        // Si es admin, solo permitir auto-edición
        if ($isTargetAdmin && $user->id !== Auth::id()) {
            $this->error('Acceso Denegado', 'No se permite editar perfiles de administradores', position: 'toast-top toast-end');
            return;
        }

        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();

        // Obtener solo permisos directos del usuario (user_permissions)
        $this->selectedPermissions = $user->directPermissions->pluck('id')->toArray();

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

            // Verificar que no se esté intentando asignar el rol admin
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && in_array($adminRole->id, $this->selectedRoles)) {
                $this->error('Acceso Denegado', 'No se permite asignar el rol de administrador', position: 'toast-top toast-end');
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

            // Verificar que no se esté intentando agregar o quitar el rol admin
            $adminRole = Role::where('name', 'admin')->first();
            $hadAdminRole = $this->editingUser->roles()->where('name', 'admin')->exists();
            $willHaveAdminRole = in_array($adminRole?->id, $this->selectedRoles);

            if ($hadAdminRole !== $willHaveAdminRole) {
                $this->error('Acceso Denegado', 'No se permite agregar o quitar el rol de administrador', position: 'toast-top toast-end');
                return;
            }

            $this->editingUser->update([
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->is_active,
            ]);

            // Asignar roles
            $this->editingUser->roles()->sync($this->selectedRoles);

            // Sincronizar permisos directos del usuario (solo si es admin)
            if (Auth::user()->hasRole('admin')) {
                $this->editingUser->directPermissions()->sync($this->selectedPermissions);
            }

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

        // Prevenir desactivación de usuarios admin
        if ($user->hasRole('admin')) {
            $this->error('Acceso Denegado', 'No se permite desactivar usuarios administradores', position: 'toast-top toast-end');
            return;
        }

        // Cambiar el estado en lugar de eliminar
        $user->update(['is_active' => 0]);
        $this->loadUsers();
        $this->warning('Usuario desactivado', 'El usuario ha sido desactivado correctamente', position: 'toast-top toast-end');
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
        return view('livewire.users')
            ->with('users', $this->loadUsers());
    }
}
