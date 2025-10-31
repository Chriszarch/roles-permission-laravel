<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\Permission;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('Permisos')]
class Permissions extends Component
{
    use Toast;
    use WithPagination;

    public $headers = [];

    public $myModal2 = false;

    public $permissionId = null;

    public $isEditing = false;

    #[Validate('required|exists:modules,id')]
    public $module_id = null;

    #[Validate('required|string|max:255')]
    public $action = '';

    #[Validate('required|string|max:255|unique:permissions,permission_key')]
    public $permission_key = '';

    public function mount(): void
    {
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'hidden lg:table-cell'],
            ['key' => 'module_name', 'label' => 'Módulo', 'class' => ''],
            ['key' => 'action', 'label' => 'Acción', 'class' => ''],
            ['key' => 'permission_key', 'label' => 'Clave de Permiso', 'class' => 'hidden lg:table-cell'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->myModal2 = true;
    }

    public function openEditModal($id): void
    {
        if ($permission = Permission::find($id)) {
            $this->permissionId = $permission->id;
            $this->module_id = $permission->module_id;
            $this->action = $permission->action;
            $this->permission_key = $permission->permission_key;
            $this->isEditing = true;
            $this->myModal2 = true;
        } else {
            $this->toast('error', 'Permiso no encontrado.');
        }
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->validate([
                'module_id' => 'required|exists:modules,id',
                'action' => 'required|string|max:255',
                'permission_key' => 'required|string|max:255|unique:permissions,permission_key,'.$this->permissionId,
            ]);
            $this->update();
        } else {
            $this->validate();
            $this->create();
        }
    }

    public function create(): void
    {
        Permission::create([
            'module_id' => $this->module_id,
            'action' => $this->action,
            'permission_key' => $this->permission_key,
        ]);

        $this->toast('success', 'Nuevo permiso creado correctamente.');
        $this->myModal2 = false;
        $this->resetForm();
    }

    public function update(): void
    {
        $permission = Permission::find($this->permissionId);
        if ($permission) {
            $permission->update([
                'module_id' => $this->module_id,
                'action' => $this->action,
                'permission_key' => $this->permission_key,
            ]);

            $this->toast('success', 'Permiso actualizado correctamente.');
            $this->myModal2 = false;
            $this->resetForm();
        } else {
            $this->toast('error', 'Permiso no encontrado.');
        }
    }

    public function resetForm(): void
    {
        $this->permissionId = null;
        $this->module_id = null;
        $this->action = '';
        $this->permission_key = '';
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $permissions = Permission::with('module')
            ->paginate(10)
            ->through(function ($permission) {
                return [
                    'id' => $permission->id,
                    'module_name' => $permission->module->name ?? 'N/A',
                    'action' => $permission->action,
                    'permission_key' => $permission->permission_key,
                    'original' => $permission,
                ];
            });

        $modules = Module::orderBy('name')->get();

        return view('livewire.permissions', [
            'permissions' => $permissions,
            'modules' => $modules,
        ]);
    }
}
