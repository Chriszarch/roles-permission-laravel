<div>
    <x-table :headers="$headers" :rows="$users" striped>
        @scope('cell_is_active', $user)
        @if ($user->is_active)
        <x-badge value="Activo" class="badge-success" />
        @else
        <x-badge value="Inactivo" class="badge-error" />
        @endif
        @endscope

        @scope('actions', $user)
        <div class="flex gap-2">
            @can('update', $user)
            <x-button class="btn-sm btn-primary" icon="o-pencil" wire:click="edit({{ $user->id }})" spinner
                tooltip="Editar usuario" />
            @endcan

            @if ($user->is_active)
            @can('deactivate', $user)
            <x-button class="btn-sm btn-error" icon="o-x-mark" wire:click="delete({{ $user->id }})" spinner
                tooltip="Desactivar usuario" onclick="return confirm('¿Está seguro de desactivar este usuario?')" />
            @endcan
            @else
            @can('update', $user)
            <x-button class="btn-sm btn-success" icon="o-check" wire:click="activate({{ $user->id }})" spinner
                tooltip="Activar usuario" />
            @endcan
            @endif
        </div>
        @endscope
    </x-table>

    <x-modal wire:model="showEditModal" title="Editar Usuario" class="backdrop-blur">
        <div class="space-y-4">
            <x-input label="Nombre" wire:model="name" placeholder="Nombre del usuario" icon="o-user" />

            <x-input label="Email" wire:model="email" placeholder="email@ejemplo.com" icon="o-envelope"
                type="email" />
        </div>

        
        <div class="mt-4">
            <h3 class="font-semibold mb-2">Asignar Roles</h3>
            <x-choices wire:model="selectedRoles" :options="$roles" clearable />
        </div>


        <x-slot:actions>
            <x-button label="Cancelar" wire:click="cancelEdit" />
            <x-button label="Guardar" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>