<div>
    <div class="w-full flex justify-between">
        <p class="text-white mt-1">Administra los roles del sistema</p>
        @can('roles.create')
            <x-button label="Agregar Rol" icon="o-plus" wire:click="openCreateModal" class="mb-4 btn-primary" />
        @endcan
    </div>

    <x-card class="mb-4 bg-neutral text-black">
        {{-- Roles table --}}
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$roles" with-pagination>

                @scope('cell_status', $role)
                    <x-badge value="{{ $role->is_active ? 'Activo' : 'Inactivo' }}"
                        class="{{ $role->is_active ? 'badge font-semibold text-teal-700 border-teal-400' : 'badge font-semibold text-pink-500 border-rose-300' }}" />
                @endscope

                @can('roles.edit')
                    @scope('actions', $role)
                        <x-button class="btn-sm hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300" icon="o-pencil" wire:click="openEditModal({{ $role->id }})" tooltip="Editar rol" spinner />
                    @endscope
                @endcan
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
