<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <p class="text-black">Bienvenido aqui esta la informacion más relevante de tu negocio</p>
        </div>
        <div class="flex gap-2">
            <x-dropdown class="bg-primary">
                <x-slot:trigger>
                    <x-button class="btn-primary btn-md bg-gradient-to-r from-primary to-secondary hover:from-primary hover:to-secondary" icon="o-calendar-days">
                        {{ $this->periods[$selectedPeriod] }}
                    </x-button>
                </x-slot:trigger>
                @foreach ($this->periods as $value => $label)
                    <x-menu-item 
                        :title="$label"
                        icon="{{ $selectedPeriod === $value ? 'o-check' : '' }}"
                        wire:click="updatePeriod('{{ $value }}')"
                        class="text-white hover:bg-secondary"
                    />
                @endforeach
            </x-dropdown>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($this->stats as $stat)
            <x-card class="p-6 hover:shadow-lg transition-shadow bg-primary">
                <div class="flex items-center justify-between">
                    <div class="font-inter text-white">
                        <p class="text-sm font-medium">{{ $stat['title'] }}</p>
                        <p class="text-3xl font-bold mt-2">{{ $stat['value'] }}</p>
                        <div class="flex items-center mt-2">
                            <span
                                class="text-sm font-medium {{ $stat['positive'] ? 'text-green-600' : 'text-red-600' }}">
                                {{ $stat['change'] }}
                            </span>
                            <span class="text-sm ml-1">vs período anterior</span>
                        </div>
                    </div>
                    <div class="p-3 {{ $stat['bg'] }} rounded-full">
                        <x-icon name="{{ $stat['icon'] }}" class="w-6 h-6 {{ $stat['color'] }}" />
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>

    <!-- Charts -->
    <div class="grid w-full gap-6">
        <!-- Scans Chart -->
        <x-card class="p-6 bg-primary">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">
                    Histórico de Escaneos
                </h3>
            </div>

            <!-- Chart -->
            <div class="relative h-[400px] bg-neutral rounded-lg p-4">
                @if (count($this->chartData['labels']) > 0)
                    <div class="flex items-end justify-between h-full">
                        @foreach ($this->chartData['labels'] as $index => $label)
                            <div class="flex flex-col items-center flex-1 group">
                                {{-- Tooltip --}}
                                <div class="absolute bottom-full mb-2 hidden group-hover:block">
                                    <div class="bg-gray-900 text-white text-xs rounded py-1 px-2">
                                        <p class="font-medium">{{ $this->chartData['datasets'][0]['data'][$index] }} escaneos</p>
                                        <p class="text-gray-300">{{ $label }}</p>
                                    </div>
                                </div>
                                
                                {{-- Bar --}}
                                <div class="bg-gradient-to-t from-primary to-secondary hover:from-primary hover:to-secondary rounded-t-lg mb-2 transition-all cursor-help"
                                    style="height: {{ ($this->chartData['datasets'][0]['data'][$index] / max($this->chartData['datasets'][0]['data'])) * 320 }}px; width: {{ $this->selectedPeriod === 'today' ? '40px' : '24px' }};">
                                </div>
                                
                                {{-- Label --}}
                                <span class="text-xs text-gray-600 dark:text-gray-400 {{ $this->selectedPeriod === 'today' ? 'rotate-0' : '-rotate-45 origin-top-left translate-y-6' }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center h-full">
                        <p class="text-gray-500 dark:text-gray-400">No hay datos para mostrar en este período</p>
                    </div>
                @endif
            </div>
        </x-card>
    </div>
</div>
