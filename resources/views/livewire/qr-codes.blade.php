@php
    $qrOpFilters = [
            ['id' => 'all', 'name' => 'Todos'],
            ['id' => 'active', 'name' => 'Activos'],
    ];

    if (Session::get('active_role') === 'admin') {
        $qrOpFilters[] = ['id' => 'inactive', 'name' => 'Inactivos'];
    }
@endphp

<div>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Mis Códigos QR</h1>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-1">Gestiona todos tus códigos QR dinámicos</p>
            </div>
            <x-button 
                label="Crear Nuevo QR" 
                icon="o-plus" 
                class="btn-primary font-medium" 
                wire:click="create" 
            />
        </div>

        {{-- Buscador y Filtros --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <x-input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar por nombre o descripción..." 
                    icon="o-magnifying-glass"
                    class="w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                />
            </div>
            <div class="flex gap-2">
                <x-select 
                    wire:model.live="statusFilter" 
                    :options="$qrOpFilters"
                    option-value="id"
                    option-label="name"
                    class="select-sm min-w-32 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
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
                    class="select-sm w-20 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                />
            </div>
        </div>
    </div>

    {{-- QR Codes List --}}
    <div class="space-y-4">
        @forelse ($qrCodes as $qrCode)
        <div class="card bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-primary-300 dark:hover:border-primary-600 transition-all duration-200">
            <div class="card-body p-6">
                <div class="flex items-start gap-6">
                    {{-- QR Preview Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/30 dark:to-primary-800/20 rounded-xl flex items-center justify-center ring-1 ring-primary-200 dark:ring-primary-700/50 shadow-sm">
                            <x-icon name="o-qr-code" class="w-10 h-10 text-primary-600 dark:text-primary-400" />
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        {{-- Title and UUID --}}
                        <div class="mb-3">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100 mb-1">
                                        {{ $qrCode->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-mono tracking-wide">
                                        {{ $qrCode->uuid }}
                                    </p>
                                </div>
                                
                                {{-- Menu de opciones --}}
                                <x-dropdown>
                                    <x-slot:trigger>
                                        <x-button icon="o-ellipsis-vertical" class="btn-ghost btn-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" />
                                    </x-slot:trigger>
                                    <x-menu-item title="Editar" icon="o-pencil" wire:click="edit({{ $qrCode->id }})" />
                                    <x-menu-separator />
                                    <x-menu-item title="Eliminar" icon="o-trash" 
                                                 wire:click="delete({{ $qrCode->id }})" 
                                                 wire:confirm="¿Estás seguro de eliminar este código QR?"
                                                 class="text-error" />
                                </x-dropdown>
                            </div>
                        </div>

                        {{-- URL with Link Icon --}}
                        <div class="flex items-center gap-2 mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <x-icon name="o-link" class="w-4 h-4 flex-shrink-0 text-gray-400 dark:text-gray-500" />
                            <a href="{{ $qrCode->uri }}" target="_blank" 
                               class="truncate hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                {{ $qrCode->uri }}
                            </a>
                        </div>

                        {{-- Tags y Metadata --}}
                        <div class="flex flex-wrap items-center gap-3 text-xs font-medium">
                            {{-- Estado --}}
                            @if ($qrCode->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800/30 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 dark:bg-green-400"></span>
                                    activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800/30 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 dark:bg-red-400"></span>
                                    inactivo
                                </span>
                            @endif

                            {{-- Botón de descarga --}}
                            <a href="{{ route('qr.download', $qrCode) }}" 
                               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-white bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700">
                                <x-icon name="o-arrow-down-tray" class="w-3.5 h-3.5" />
                                <span>Descargar</span>
                            </a>

                            {{-- Fecha de creación --}}
                            <span class="text-gray-500 dark:text-gray-500">
                                Creado {{ $qrCode->created_at->format('d M Y') }}
                            </span>
                        </div>

                        {{-- Descripción (opcional - puedes agregar después) --}}
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">
                            Código QR para el menú digital del restaurante
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="card-body p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <x-icon name="o-qr-code" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100 mb-2">
                    No hay códigos QR
                </h3>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">
                    @if($search)
                        No se encontraron códigos QR que coincidan con "{{ $search }}"
                    @else
                        Comienza creando tu primer código QR dinámico
                    @endif
                </p>
                @if(!$search)
                    <x-button 
                        label="Crear Código QR" 
                        icon="o-plus" 
                        class="btn-primary font-medium bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 shadow-lg" 
                        wire:click="create" 
                    />
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Modal para crear/editar QR --}}
    <x-modal wire:model="showModal" title="{{ $editingQrCode ? 'Editar Código QR' : 'Nuevo Código QR' }}" 
             class="backdrop-blur bg-white dark:bg-gray-800">
        <x-form wire:submit="save" class="space-y-4">
            <x-input 
                label="Nombre" 
                wire:model="name" 
                placeholder="Ej: Menu Principal" 
                hint="Nombre descriptivo para identificar el código QR"
                class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
            />
            
            <x-input 
                label="URL de destino" 
                wire:model="uri" 
                placeholder="https://ejemplo.com" 
                hint="URL a la que redirigirá el código QR"
                class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
            />
            
            <div class="flex items-center gap-2 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                <x-checkbox 
                    label="Activo" 
                    wire:model="is_active"
                    class="checkbox-primary"
                />
                <span class="text-xs text-gray-500 dark:text-gray-400">El código QR estará disponible para escanear</span>
            </div>

            <x-slot:actions>
                <x-button 
                    label="Cancelar" 
                    @click="$wire.showModal = false"
                    class="btn-ghost text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                />
                <x-button 
                    label="Guardar" 
                    class="btn-primary font-medium shadow-sm bg-primary-600 hover:bg-primary-700 text-white" 
                    type="submit" 
                    spinner="save" 
                />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>