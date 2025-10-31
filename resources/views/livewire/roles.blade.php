<div>
    <div class="w-full flex justify-between">
        <p class="text-gray-600 dark:text-gray-400 mt-1">Administra los roles del sistema</p>
        <x-button label="Agregar Rol" icon="o-plus" wire:click="openCreateModal" class="mb-4 btn-primary" />
    </div>

    <x-card class="mb-4">
        {{-- Roles table --}}
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$roles" with-pagination>

            @scope('cell_status', $role)
                <x-badge value="{{ $role->is_active ? 'Activo' : 'Inactivo' }}"
                    class="{{ $role->is_active ? 'badge-primary' : 'badge-soft' }}" />
            @endscope

            @scope('actions', $role)
                <x-button icon="o-pencil" wire:click="openEditModal({{ $role->id }})" spinner class="btn-sm" />
            @endscope
            </x-table>
        </div>

    </x-card>

    {{-- Create/Edit Role Modal --}}

    <x-modal wire:model="myModal2" :title="$isEditing ? 'Editar Rol' : 'Crear Rol'">
        <x-form wire:submit.prevent="save" no-separator>
            <x-input label="Nombre del rol" wire:model="name" />
            <x-textarea label="Descripción" wire:model="description" />
            <x-checkbox label="Activo" wire:model="active" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.myModal2 = false" />
                <x-button label="{{ $isEditing ? 'Actualizar' : 'Crear' }}" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
