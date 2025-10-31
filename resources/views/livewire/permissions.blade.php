<div>
    <div class="w-full flex justify-between">
        <p class="text-gray-600 dark:text-gray-400 mt-1">Administra los permisos del sistema</p>

        @can('permissions.create')
            <x-button label="Agregar Permiso" icon="o-plus" wire:click="openCreateModal" class="mb-4 btn-primary" />
        @endcan
    </div>

    <x-card class="mb-4">
        {{-- Permissions table --}}
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$permissions" with-pagination>
                @can('permissions.edit')
                    @scope('actions', $permission)
                        <x-button icon="o-pencil" wire:click="openEditModal({{ $permission['original']->id }})" spinner
                            class="btn-sm" />
                    @endscope
                @endcan
            </x-table>
        </div>

    </x-card>

    {{-- Create/Edit Permission Modal --}}

    <x-modal wire:model="myModal2" :title="$isEditing ? 'Editar Permiso' : 'Crear Permiso'">
        <x-form wire:submit.prevent="save" no-separator>
            <x-select label="Módulo" wire:model="module_id" :options="$modules" option-value="id" option-label="name"
                placeholder="Selecciona un módulo" />

            <x-input label="Acción" wire:model="action" placeholder="ej: view, create, edit, delete" />

            <x-input label="Clave de Permiso" wire:model="permission_key" placeholder="ej: users.view, roles.create" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.myModal2 = false" />
                <x-button label="{{ $isEditing ? 'Actualizar' : 'Crear' }}" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
