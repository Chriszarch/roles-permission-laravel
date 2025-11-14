<div>
    <div class="w-full flex justify-between mb-4">
        <p class="text-white mt-1">Administra los usuarios del sistema</p>
        @can('users.create')
            <x-button label="Agregar Usuario" icon="o-plus" wire:click="openCreateModal" class="btn-primary" />
        @endcan
    </div>

    <x-card class="mb-4 bg-primary text-white">
        {{-- Users table --}}
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$users" class="bg-neutral rounded-md text-black" with-pagination>
                
                @scope('cell_is_active', $user)
                    @if ($user['is_active'])
                        <x-badge value="Activo" class="badge font-semibold text-teal-700 border-teal-400" />
                    @else
                        <x-badge value="Inactivo" class="badge font-semibold text-pink-500 border-rose-300" />
                    @endif
                @endscope

                @scope('actions', $user)
                    <div class="flex gap-2">
                        @can('users.edit')
                            <x-button class="btn-sm hover:bg-primary hover:text-primary-600 hover:border-primary-300" icon="o-pencil" wire:click="edit({{ $user['id'] }})" spinner
                                tooltip="Editar usuario" />
                        @endcan

                        @if ($user['is_active'])
                            @can('users.delete')
                                <x-button class="btn-sm bg-pink-50 text-pink-400 border-rose-200" icon="o-x-mark" wire:click="delete({{ $user['id'] }})" spinner
                                    tooltip="Desactivar usuario" onclick="return confirm('¿Está seguro de desactivar este usuario?')" />
                            @endcan
                        @else
                            @can('users.edit')
                                <x-button class="btn-sm bg-teal-50 text-teal-600 border-teal-300" icon="o-check" wire:click="activate({{ $user['id'] }})" spinner
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
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4 -mx-6 -mt-6 mb-6 border-b border-primary/20">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Usuario</h2>
        </div>

        <x-form wire:submit.prevent="create" no-separator>
            <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                {{-- Información Básica --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <x-icon name="o-user" class="w-5 h-5 text-primary" />
                        Información Básica
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input 
                            label="Nombre" 
                            wire:model="name" 
                            placeholder="Nombre del usuario" 
                            icon="o-user"
                            class="text-white border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                        />
                        <x-input 
                            label="Email" 
                            wire:model="email" 
                            placeholder="email@ejemplo.com" 
                            icon="o-envelope"
                            type="email"
                            class="text-white border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                        />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <x-input 
                            label="Contraseña" 
                            wire:model="password" 
                            placeholder="••••••••" 
                            icon="o-key"
                            type="password"
                            class="text-white border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                        />
                        <x-input 
                            label="Confirmar Contraseña" 
                            wire:model="password_confirmation" 
                            placeholder="••••••••"
                            icon="o-key" 
                            type="password"
                            class="text-white border-gray-300 focus:border-gray-500 focus:ring-gray-500"
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
                    <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
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
                    <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
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

            <div class="flex justify-end gap-3 pt-6 mt-6 border-t -mx-6 -mb-6 px-6 py-4 sticky bottom-0">
                <x-button label="Cancelar" @click="$wire.showCreateModal = false" />
                <x-button label="Crear Usuario" icon="o-check" class="btn-primary bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700" type="submit" spinner="create" />
            </div>
        </x-form>
    </x-modal>

    {{-- Modal Editar Usuario --}}
    <x-modal wire:model="showEditModal" class="backdrop-blur-sm">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4 -mx-6 -mt-6 mb-6 border-b border-primary/20">
            <h2 class="text-2xl font-bold text-white">Editar Usuario</h2>
        </div>

        <x-form class="dark:bg-gray-900 text-gray-900 dark:text-gray-100" wire:submit.prevent="save" no-separator>
            <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                {{-- Información Básica --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <x-icon name="o-user" class="w-5 h-5 text-primary" />
                        Información Básica
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input 
                            label="Nombre" 
                            wire:model="name" 
                            placeholder="Nombre del usuario" 
                            icon="o-user"
                            class="text-white border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-primary"
                        />
                        <x-input 
                            label="Email" 
                            wire:model="email" 
                            placeholder="email@ejemplo.com" 
                            icon="o-envelope"
                            type="email"
                            disabled
                            class="text-white border-gray-300 dark:border-gray-60 text-gray-300"
                        />
                    </div>
                    <div class="flex items-center justify-between mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="text-sm font-semibold text-gray-900 dark:text-white">Usuario Activo</label>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                Desactiva el usuario para bloquear su acceso
                            </p>
                        </div>
                        <x-toggle wire:model="is_active" />
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                {{-- Roles --}}
                <div class="text-white">
                    <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                        <x-icon name="o-shield-check" class="w-5 h-5 text-secondary" />
                        Roles
                    </h3>
                    <p class="text-sm mb-4">
                        Selecciona uno o más roles para el usuario
                    </p>
                    <x-choices 
                        wire:model="selectedRoles" 
                        :options="$roles" 
                        icon="o-user-group"
                        searchable
                    />
                </div>

                @if(auth()->user()->hasRole('admin'))
                    <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                    {{-- Permisos Directos del Usuario (Solo Admin) --}}
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
                            <x-icon name="o-lock-closed" class="w-5 h-5 text-amber-600 dark:text-amber-500" />
                            Permisos Directos del Usuario
                        </h3>
                        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-lg p-4 mb-4">
                            <div class="flex gap-3">
                                <x-icon name="o-exclamation-triangle" class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" />
                                <div>
                                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">Permisos Exclusivos</p>
                                    <p class="text-xs text-amber-800 dark:text-amber-300 mt-1">
                                        Estos permisos tienen <strong>máxima prioridad</strong> y prevalecen sobre los permisos asignados por roles. 
                                        Úsalos solo para casos excepcionales.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @foreach ($modules as $module)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-primary/50 dark:hover:border-primary/50 transition-colors dark:bg-gray-800/50">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20 flex items-center justify-center">
                                                <x-icon name="o-cube" class="w-5 h-5 text-primary dark:text-primary" />
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-white">{{ $module->name }}</h4>
                                                @if ($module->description)
                                                    <p class="text-sm text-white">{{ $module->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                                        @foreach ($module->permissions as $permission)
                                            <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                                    class="checkbox checkbox-sm checkbox-primary" />
                                                <span class="text-sm font-medium text-white">
                                                    {{ $permission->action }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-6 mt-6 border-t -mx-6 -mb-6 px-6 py-4 sticky bottom-0">
                <x-button label="Cancelar" @click="$wire.showEditModal = false" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600" />
                <x-button label="Guardar Cambios" icon="o-check" class="btn-primary bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white shadow-md" type="submit" spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>