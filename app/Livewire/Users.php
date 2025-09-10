<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Users extends Component
{
    use AuthorizesRequests;

    public $headers = [];
    public $users = [];
    public $title = 'Usuarios';

    // Variables para el modal de edición
    public $showEditModal = false;
    public $editingUser = null;
    public $name = '';
    public $email = '';
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

    // ==============================================
    // MÉTODOS DE EDICIÓN
    // ==============================================

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('update', $user);

        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->authorize('update', $this->editingUser);
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->editingUser->id,
        ]);

        $this->editingUser->update([
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ]);

        $this->showEditModal = false;
        $this->loadUsers();
        $this->dispatch('user-updated', 'Usuario actualizado correctamente');
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
            $this->dispatch('user-edit', 'Usuario desactivado correctamente');
        }
    }

    public function activate($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('update', $user);

        $user->update(['is_active' => 1]);
        $this->loadUsers();
        $this->dispatch('user-activated', 'Usuario activado correctamente');
    }

    public function render()
    {
        return view('livewire.users');
    }
}
