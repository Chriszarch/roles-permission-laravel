<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <p class="text-black">Welcome back! Here's what's happening with your business today.</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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

            <div class="flex justify-center mt-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Revenue</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Growth</span>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Top Products -->
        <x-card class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Products</h3>
                <x-button label="View All" class="btn-ghost btn-sm" />
            </div>

            <div class="space-y-4">
                @foreach ($this->topProducts as $index => $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $product['sales'] }} sales</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $product['revenue'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <x-card class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                    <x-button label="View All" class="btn-ghost btn-sm" />
                </div>

                <div class="space-y-4">
                    @foreach ($this->recentActivities as $activity)
                        <div
                            class="flex items-center space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <img src="{{ $activity['avatar'] }}" alt="{{ $activity['user'] }}"
                                class="w-10 h-10 rounded-full">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $activity['user'] }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">{{ $activity['action'] }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $activity['time'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <!-- Quick Actions -->
        <div>
            <x-card class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>

                <div class="space-y-3">
                    <x-button label="Create New Order" icon="o-plus-circle" class="w-full btn-primary justify-start"
                        wire:click="$dispatch('toast', {title: 'Feature Coming Soon!', description: 'This is a demo dashboard.'})" />

                    <x-button label="Add New User" icon="o-user-plus" class="w-full btn-outline justify-start"
                        wire:click="$dispatch('toast', {title: 'Feature Coming Soon!', description: 'This is a demo dashboard.'})" />

                    <x-button label="Generate Report" icon="o-document-chart-bar"
                        class="w-full btn-outline justify-start"
                        wire:click="$dispatch('toast', {title: 'Feature Coming Soon!', description: 'This is a demo dashboard.'})" />

                    <x-button label="View Analytics" icon="o-chart-bar" class="w-full btn-outline justify-start"
                        wire:click="$dispatch('toast', {title: 'Feature Coming Soon!', description: 'This is a demo dashboard.'})" />
                </div>

                <!-- Progress Section -->
                <div class="mt-8">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">Monthly Goals</h4>

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Sales Target</span>
                                <span class="font-medium">78%</span>
                            </div>
                            <x-progress value="78" max="100" class="progress-primary" />
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 dark:text-gray-400">New Customers</span>
                                <span class="font-medium">65%</span>
                            </div>
                            <x-progress value="65" max="100" class="progress-secondary" />
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Revenue Goal</span>
                                <span class="font-medium">92%</span>
                            </div>
                            <x-progress value="92" max="100" class="progress-success" />
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Performance Metrics -->
    <x-card class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Performance Metrics</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">98.5%</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Uptime</div>
            </div>

            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">2.4s</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Avg Response</div>
            </div>

            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">4.8/5</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">User Rating</div>
            </div>

            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">847</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Active Users</div>
            </div>
        </div>
    </x-card>
</div>
