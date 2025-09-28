<div>
    {{-- TABLA DE USUARIOS --}}
    <x-table :headers="$headers" :rows="$users" striped>

        {{-- Mostrar estado del usuario --}}
        @scope('cell_is_active', $user)
            @if ($user->is_active)
                <x-badge value="Activo" class="badge-success" />
            @else
                <x-badge value="Inactivo" class="badge-error" />
            @endif
        @endscope

        {{-- Botones de acción --}}
        @scope('actions', $user)
            <div class="flex gap-2">
                {{-- Botón Editar --}}
                @can('update', $user)
                    <x-button class="btn-sm btn-primary" icon="o-pencil" wire:click="edit({{ $user->id }})" spinner
                        tooltip="Editar usuario" />
                @endcan

                {{-- Botón Activar/Desactivar --}}
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

    {{-- MODAL DE EDICIÓN --}}
    <x-modal wire:model="showEditModal" title="Editar Usuario" class="backdrop-blur">
        <div class="space-y-4">
            {{-- Campo Nombre --}}
            <x-input label="Nombre" wire:model="name" placeholder="Nombre del usuario" icon="o-user" />

            {{-- Campo Email --}}
            <x-input label="Email" wire:model="email" placeholder="email@ejemplo.com" icon="o-envelope"
                type="email" />
        </div>

        @can('roles-edit', App\Models\User::class)
            {{-- Asignar roles --}}
            <div class="mt-4">
                <h3 class="font-semibold mb-2">Asignar Roles</h3>
                <x-select label="Roles" :options="$roles" :options="$roles" place-holder-value="0"/>
            </div>  
            
        @endcan

        {{-- Botones del modal --}}
        <x-slot:actions>
            <x-button label="Cancelar" wire:click="cancelEdit" />
            <x-button label="Guardar" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
