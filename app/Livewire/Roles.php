<?php

namespace App\Livewire;

use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('Roles')]
class Roles extends Component
{
    use AuthorizesRequests;
    use Toast;
    use WithPagination;

    public $headers = [];

    public $myModal2 = false;

    public $roleId = null;

    public $isEditing = false;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('boolean')]
    public $active = false;

    public function mount(): void
    {
        $this->headers = [
            // Hide ID on small screens to save space
            ['key' => 'id', 'label' => '#', 'class' => 'hidden lg:table-cell'],
            // Keep name always visible
            ['key' => 'name', 'label' => 'Nombre del Rol', 'class' => ''],
            // Hide long text on very small screens
            ['key' => 'description', 'label' => 'Descripción', 'class' => 'hidden lg:table-cell'],
            // Show status from small and up
            ['key' => 'status', 'label' => 'Estado', 'class' => 'hidden lg:table-cell'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->authorize('roles.create', Role::class);
        $this->resetForm();
        $this->isEditing = false;
        $this->myModal2 = true;
    }

    public function openEditModal($id): void
    {
        if ($role = Role::find($id)) {
            $this->authorize('roles.edit', $role);

            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->description = $role->description;
            $this->active = (bool) $role->is_active;
            $this->isEditing = true;
            $this->myModal2 = true;
        } else {
            $this->toast('error', 'Rol no encontrado.');
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->authorize('roles.edit', Role::find($this->roleId));
            $this->update();
        } else {
            $this->authorize('roles.create', Role::class);
            $this->create();
        }
    }

    public function create(): void
    {
        Role::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->active,
        ]);

        $this->toast('success', 'Nuevo rol creado correctamente.');
        $this->myModal2 = false;
        $this->resetForm();
    }

    public function update(): void
    {
        $role = Role::find($this->roleId);
        if ($role) {
            $role->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->active,
            ]);

            $this->toast('success', 'Rol actualizado correctamente.');
            $this->myModal2 = false;
            $this->resetForm();
        } else {
            $this->toast('error', 'Rol no encontrado.');
        }
    }

    public function resetForm(): void
    {
        $this->roleId = null;
        $this->name = '';
        $this->description = '';
        $this->active = false;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.roles', [
            'roles' => Role::paginate(10),
        ]);
    }
}
