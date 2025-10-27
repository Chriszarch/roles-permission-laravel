<div>
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona tus códigos QR dinámicos</p>
        <x-button label="Nuevo QR" icon="o-plus" class="btn-sm btn-primary" wire:click="create" />
    </div>

    {{-- QR Codes List --}}
    <div class="space-y-4">
        @foreach ($qrCodes as $qrCode)
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body p-4">
                <div class="flex items-start gap-4">
                    {{-- QR Preview Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-base-200 rounded flex items-center justify-center">
                            <x-icon name="o-qr-code" class="w-8 h-8" />
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 space-y-3">
                        <div>
                            <h3 class="font-semibold">{{ $qrCode->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $qrCode->uuid }}</p>
                        </div>

                        <x-input prefix="Link" value="{{ $qrCode->uri }}" class="input-sm">
                            <x-slot:append>
                                <x-button label="Editar" class="join-item btn-primary" 
                                          wire:click="edit({{ $qrCode->id }})" />
                            </x-slot:append>
                        </x-input>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ route('qr.download', $qrCode) }}" download 
                               class="btn btn-xs btn-ghost gap-1">
                                <x-icon name="o-arrow-down-tray" class="w-4 h-4" />
                                Descargar
                            </a>
                            
                            @if ($qrCode->is_active)
                                <x-badge value="Activo" class="badge-success badge-sm" />
                            @else
                                <x-badge value="Inactivo" class="badge-error badge-sm" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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