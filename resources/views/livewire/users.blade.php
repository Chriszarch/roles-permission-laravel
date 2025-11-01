<div>
    <div class="w-full flex justify-between mb-4">
        <p class="text-gray-600 dark:text-gray-400 mt-1">Administra los usuarios del sistema</p>
        @can('users.create', App\Models\User::class)
            <x-button label="Agregar Usuario" icon="o-plus" wire:click="openCreateModal" class="btn-primary" />
        @endcan
    </div>

    <x-card class="mb-4">
        {{-- Users table --}}
        <div class="overflow-x-auto">
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
                        @can('users.edit', $user)
                            <x-button class="btn-sm btn-primary" icon="o-pencil" wire:click="edit({{ $user->id }})" spinner
                                tooltip="Editar usuario" />
                        @endcan

                        @if ($user->is_active)
                            @can('users.delete', $user)
                                <x-button class="btn-sm btn-error" icon="o-x-mark" wire:click="delete({{ $user->id }})" spinner
                                    tooltip="Desactivar usuario" onclick="return confirm('¿Está seguro de desactivar este usuario?')" />
                            @endcan
                        @else
                            @can('users.edit', $user)
                                <x-button class="btn-sm btn-success" icon="o-check" wire:click="activate({{ $user->id }})" spinner
                                    tooltip="Activar usuario" />
                            @endcan
                        @endif
                    </div>
                @endscope
            </x-table>
        </div>
    </x-card>

    {{-- Modal Crear Usuario --}}
    <x-modal wire:model="showCreateModal" class="backdrop-blur">
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 px-6 py-4 -mx-6 -mt-6 mb-6 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Usuario</h2>
        </div>

        <x-form wire:submit.prevent="create" no-separator>
            <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                {{-- Información Básica --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-icon name="o-user" class="w-5 h-5 text-blue-600" />
                        Información Básica
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input 
                            label="Nombre" 
                            wire:model="name" 
                            placeholder="Nombre del usuario" 
                            icon="o-user"
                            class="border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input 
                            label="Email" 
                            wire:model="email" 
                            placeholder="email@ejemplo.com" 
                            icon="o-envelope"
                            type="email"
                            class="border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-input 
                            label="Contraseña" 
                            wire:model="password" 
                            placeholder="••••••••" 
                            icon="o-key"
                            type="password"
                            class="border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input 
                            label="Confirmar Contraseña" 
                            wire:model="password_confirmation" 
                            placeholder="••••••••"
                            icon="o-key" 
                            type="password"
                            class="border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                    <div class="flex items-center justify-between mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Usuario Activo</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Desactiva el usuario para bloquear su acceso
                            </p>
                        </div>
                        <x-toggle wire:model="is_active" />
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                {{-- Roles --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <x-icon name="o-shield-check" class="w-5 h-5 text-purple-600" />
                        Roles
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Selecciona uno o más roles para el usuario
                    </p>
                    <x-choices 
                        wire:model="selectedRoles" 
                        :options="$roles" 
                        icon="o-user-group"
                        searchable
                    />
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                {{-- Permisos por Módulo --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <x-icon name="o-lock-closed" class="w-5 h-5 text-orange-600" />
                        Permisos Adicionales
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Selecciona permisos adicionales que se agregarán a los roles seleccionados
                    </p>
                    <div class="space-y-4">
                        @foreach ($modules as $module)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-blue-300 dark:hover:border-blue-500 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/50 dark:to-cyan-900/50 flex items-center justify-center">
                                            <x-icon name="o-cube" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $module->name }}</h4>
                                            @if ($module->description)
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $module->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                                    @foreach ($module->permissions as $permission)
                                        <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                                class="checkbox checkbox-sm checkbox-primary" />
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $permission->action }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 -mx-6 -mb-6 px-6 py-4 sticky bottom-0">
                <x-button label="Cancelar" @click="$wire.showCreateModal = false" />
                <x-button label="Crear Usuario" icon="o-check" class="btn-primary bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700" type="submit" spinner="create" />
            </div>
        </x-form>
    </x-modal>

    {{-- Modal Editar Usuario --}}
    <x-modal wire:model="showEditModal" class="backdrop-blur">
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 px-6 py-4 -mx-6 -mt-6 mb-6 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Usuario</h2>
        </div>

        <x-form wire:submit.prevent="save" no-separator>
            <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                {{-- Información Básica --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-icon name="o-user" class="w-5 h-5 text-blue-600" />
                        Información Básica
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input 
                            label="Nombre" 
                            wire:model="name" 
                            placeholder="Nombre del usuario" 
                            icon="o-user"
                            class="border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input 
                            label="Email" 
                            wire:model="email" 
                            placeholder="email@ejemplo.com" 
                            icon="o-envelope"
                            type="email"
                            disabled
                            class="border-gray-300 bg-gray-50 dark:bg-gray-800"
                        />
                    </div>
                    <div class="flex items-center justify-between mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Usuario Activo</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Desactiva el usuario para bloquear su acceso
                            </p>
                        </div>
                        <x-toggle wire:model="is_active" />
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                {{-- Roles --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <x-icon name="o-shield-check" class="w-5 h-5 text-purple-600" />
                        Roles
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Selecciona uno o más roles para el usuario
                    </p>
                    <x-choices 
                        wire:model="selectedRoles" 
                        :options="$roles" 
                        icon="o-user-group"
                        searchable
                    />
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                {{-- Permisos por Módulo --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <x-icon name="o-lock-closed" class="w-5 h-5 text-orange-600" />
                        Permisos Adicionales
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Selecciona permisos adicionales que se agregarán a los roles seleccionados
                    </p>
                    <div class="space-y-4">
                        @foreach ($modules as $module)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-blue-300 dark:hover:border-blue-500 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/50 dark:to-cyan-900/50 flex items-center justify-center">
                                            <x-icon name="o-cube" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $module->name }}</h4>
                                            @if ($module->description)
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $module->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                                    @foreach ($module->permissions as $permission)
                                        <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                                class="checkbox checkbox-sm checkbox-primary" />
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $permission->action }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 -mx-6 -mb-6 px-6 py-4 sticky bottom-0">
                <x-button label="Cancelar" @click="$wire.showEditModal = false" />
                <x-button label="Guardar Cambios" icon="o-check" class="btn-primary bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700" type="submit" spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>