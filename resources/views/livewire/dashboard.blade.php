<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <p class="text-black">Bienvenido aqui esta la informacion más relevante de tu negocio</p>
        </div>
        <div class="flex gap-2">
            <x-button icon="o-arrow-path" class="btn-ghost btn-sm" wire:click="$refresh">
                Refresh
            </x-button>
            <x-button icon="o-calendar-days" class="btn-primary btn-sm">
                Last 30 days
            </x-button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($this->stats as $stat)
            <x-card class="p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $stat['title'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stat['value'] }}</p>
                        <div class="flex items-center mt-2">
                            <span
                                class="text-sm font-medium {{ $stat['positive'] ? 'text-green-600' : 'text-red-600' }}">
                                {{ $stat['change'] }}
                            </span>
                            <span class="text-sm text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    <div class="p-3 {{ $stat['bg'] }} rounded-full">
                        <x-icon name="{{ $stat['icon'] }}" class="w-6 h-6 {{ $stat['color'] }}" />
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>

    <!-- Charts and Analytics -->
    <div class="grid w-full gap-6">
        <!-- Revenue Chart -->
        <x-card class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Overview</h3>
                <x-dropdown>
                    <x-slot:trigger>
                        <x-button icon="o-ellipsis-horizontal" class="btn-ghost btn-sm" />
                    </x-slot:trigger>
                    <x-menu-item title="Export" icon="o-arrow-down-tray" />
                    <x-menu-item title="View Details" icon="o-eye" />
                </x-dropdown>
            </div>

            <!-- Simulated Chart -->
            <div
                class="relative h-64 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-lg p-4">
                <div class="flex items-end justify-between h-full">
                    @foreach ($this->chartData['labels'] as $index => $label)
                        <div class="flex flex-col items-center flex-1">
                            <div class="bg-gradient-to-t from-blue-500 to-purple-500 rounded-t-lg mb-2"
                                style="height: {{ ($this->chartData['revenue'][$index] / max($this->chartData['revenue'])) * 200 }}px; width: 24px;">
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>
    </div>
</div>
