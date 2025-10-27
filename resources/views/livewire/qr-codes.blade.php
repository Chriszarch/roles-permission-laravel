<div>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mis Códigos QR</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestiona todos tus códigos QR dinámicos</p>
            </div>
            <x-button label="Crear Nuevo QR" icon="o-plus" class="btn-primary" wire:click="create" />
        </div>

        {{-- Buscador y Filtros --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <x-input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar por nombre o descripción..." 
                    icon="o-magnifying-glass"
                    class="w-full"
                />
            </div>
            <div class="flex gap-2">
                <x-select 
                    wire:model.live="statusFilter" 
                    :options="[
                        ['id' => 'all', 'name' => 'Todos'],
                        ['id' => 'active', 'name' => 'Activos'],
                        ['id' => 'inactive', 'name' => 'Inactivos']
                    ]"
                    option-value="id"
                    option-label="name"
                    class="select-sm min-w-32"
                />
                <x-select 
                    wire:model.live="perPage" 
                    :options="[
                        ['id' => 5, 'name' => '5'],
                        ['id' => 10, 'name' => '10'],
                        ['id' => 25, 'name' => '25'],
                        ['id' => 50, 'name' => '50']
                    ]"
                    option-value="id"
                    option-label="name"
                    class="select-sm w-20"
                />
            </div>
        </div>
    </div>

    {{-- QR Codes List --}}
    <div class="space-y-4">
        @forelse ($qrCodes as $qrCode)
        <div class="card bg-base-100 shadow-sm border border-base-300 hover:shadow-md transition-shadow">
            <div class="card-body p-6">
                <div class="flex items-start gap-6">
                    {{-- QR Preview Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-primary/10 rounded-lg flex items-center justify-center">
                            <x-icon name="o-qr-code" class="w-10 h-10 text-primary" />
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        {{-- Title and UUID --}}
                        <div class="mb-3">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                        {{ $qrCode->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-mono truncate">
                                        {{ $qrCode->uuid }}
                                    </p>
                                </div>
                                
                                {{-- Menu de opciones --}}
                                <x-dropdown>
                                    <x-slot:trigger>
                                        <x-button icon="o-ellipsis-vertical" class="btn-ghost btn-sm" />
                                    </x-slot:trigger>
                                    <x-menu-item title="Editar" icon="o-pencil" wire:click="edit({{ $qrCode->id }})" />
                                    <x-menu-item title="Descargar QR" icon="o-arrow-down-tray" 
                                                 link="{{ route('qr.download', $qrCode) }}" />
                                    <x-menu-separator />
                                    <x-menu-item title="Eliminar" icon="o-trash" 
                                                 wire:click="delete({{ $qrCode->id }})" 
                                                 wire:confirm="¿Estás seguro de eliminar este código QR?"
                                                 class="text-error" />
                                </x-dropdown>
                            </div>
                        </div>

                        {{-- URL with Link Icon --}}
                        <div class="flex items-center gap-2 mb-3 text-sm text-gray-600 dark:text-gray-300">
                            <x-icon name="o-link" class="w-4 h-4 flex-shrink-0" />
                            <a href="{{ $qrCode->uri }}" target="_blank" 
                               class="truncate hover:text-primary transition-colors">
                                {{ $qrCode->uri }}
                            </a>
                        </div>

                        {{-- Tags y Metadata --}}
                        <div class="flex flex-wrap items-center gap-3 text-xs">
                            {{-- Badge de categoría simulado (puedes agregar campo category después) --}}
                            <span class="badge badge-sm bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                menu
                            </span>
                            
                            {{-- Estado --}}
                            @if ($qrCode->is_active)
                                <span class="badge badge-sm badge-success gap-1">
                                    activo
                                </span>
                            @else
                                <span class="badge badge-sm badge-error gap-1">
                                    inactivo
                                </span>
                            @endif

                            {{-- Botón de descarga --}}
                            <a href="{{ route('qr.download', $qrCode) }}" 
                               class="flex items-center gap-1 px-2 py-1 rounded hover:bg-base-200 transition-colors text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary">
                                <x-icon name="o-arrow-down-tray" class="w-3.5 h-3.5" />
                                <span>Descargar</span>
                            </a>

                            {{-- Fecha de creación --}}
                            <span class="text-gray-500 dark:text-gray-400">
                                Creado {{ $qrCode->created_at->format('d M Y') }}
                            </span>
                        </div>

                        {{-- Descripción (opcional - puedes agregar después) --}}
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                            Código QR para el menú digital del restaurante
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body p-12 text-center">
                <x-icon name="o-qr-code" class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    No hay códigos QR
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    @if($search)
                        No se encontraron códigos QR que coincidan con "{{ $search }}"
                    @else
                        Comienza creando tu primer código QR dinámico
                    @endif
                </p>
                @if(!$search)
                    <x-button label="Crear Código QR" icon="o-plus" class="btn-primary" wire:click="create" />
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Modal para crear/editar QR --}}
    <x-modal wire:model="showModal" title="{{ $editingQrCode ? 'Editar Código QR' : 'Nuevo Código QR' }}" 
             class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Nombre" wire:model="name" placeholder="Ej: Menu Principal" 
                     hint="Nombre descriptivo para identificar el código QR" />
            
            <x-input label="URL de destino" wire:model="uri" placeholder="https://ejemplo.com" 
                     hint="URL a la que redirigirá el código QR" />
            
            <x-checkbox label="Activo" wire:model="is_active" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.showModal = false" />
                <x-button label="Guardar" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>