<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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

    public $title = 'Usuarios';

    // Variables para el modal de edición
    public $showEditModal = false;

    public $editingUser = null;

    public $name = '';

    public $email = '';

    public $selectedRoles = [];

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
    }

    public function loadUsers()
    {
        $this->users = User::all();
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $roles = Role::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();

        $this->roles = $roles;
        $this->selectedRoles = $userRoleIds;
        $this->authorize('update', $user);

        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->showEditModal = true;
    }

    public function save()
    {
        try {
            $this->authorize('update', $this->editingUser);
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$this->editingUser->id,
            ]);

            $this->editingUser->update([
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->is_active,
            ]);

            if (! empty($this->selectedRoles)) {
                $this->editingUser->roles()->sync($this->selectedRoles);
            } else {
                $this->error('Error', 'Debe asignar al menos un rol al usuario', position: 'toast-top toast-end');

                return;
            }

            $this->showEditModal = false;
            $this->loadUsers();
            $this->success('Usuario actualizado', 'El usuario ha sido actualizado correctamente', position: 'toast-top toast-end');
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
        $this->reset(['name', 'email', 'is_active', 'editingUser']);
    }

    // ==============================================
    // MÉTODO DE "ELIMINACIÓN" (CAMBIAR ESTADO)
    // ==============================================

    public function delete($userId)
    {
        $user = User::findOrFail($userId);

        $this->authorize('deactivate', $user);
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
        $this->authorize('update', $user);

        $user->update(['is_active' => 1]);
        $this->loadUsers();
        $this->success('Usuario activado', 'El usuario ha sido activado correctamente', position: 'toast-top toast-end');
    }

    public function render()
    {
        return view('livewire.users');
    }
}
