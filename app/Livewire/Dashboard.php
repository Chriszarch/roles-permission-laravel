<?php

namespace App\Livewire;

use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    public string $title = 'Dashboard';

    // Dashboard stats (hardcoded)
    public function getStatsProperty(): array
    {
        return [
            [
                'title' => 'Total Users',
                'value' => '2,847',
                'change' => '+12%',
                'icon' => 'o-users',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
                'positive' => true
            ],
            [
                'title' => 'Revenue',
                'value' => '$89,472',
                'change' => '+8.3%',
                'icon' => 'o-currency-dollar',
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
                'positive' => true
            ],
            [
                'title' => 'Orders',
                'value' => '1,234',
                'change' => '-2.1%',
                'icon' => 'o-shopping-bag',
                'color' => 'text-orange-600',
                'bg' => 'bg-orange-50',
                'positive' => false
            ],
            [
                'title' => 'Active Sessions',
                'value' => '456',
                'change' => '+15.2%',
                'icon' => 'o-signal',
                'color' => 'text-purple-600',
                'bg' => 'bg-purple-50',
                'positive' => true
            ]
        ];
    }

    // Recent activities (hardcoded)
    public function getRecentActivitiesProperty(): array
    {
        return [
            [
                'user' => 'John Doe',
                'action' => 'Created new order',
                'time' => '5 min ago',
                'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=3b82f6&color=fff'
            ],
            [
                'user' => 'Jane Smith',
                'action' => 'Updated profile',
                'time' => '12 min ago',
                'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&background=10b981&color=fff'
            ],
            [
                'user' => 'Mike Johnson',
                'action' => 'Completed payment',
                'time' => '1 hour ago',
                'avatar' => 'https://ui-avatars.com/api/?name=Mike+Johnson&background=f59e0b&color=fff'
            ],
            [
                'user' => 'Sarah Wilson',
                'action' => 'Left a review',
                'time' => '2 hours ago',
                'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Wilson&background=8b5cf6&color=fff'
            ]
        ];
    }

    // Top products (hardcoded)
    public function getTopProductsProperty(): array
    {
        return [
            ['name' => 'Laptop Pro', 'sales' => 145, 'revenue' => '$87,450'],
            ['name' => 'Wireless Headphones', 'sales' => 98, 'revenue' => '$19,600'],
            ['name' => 'Smartphone', 'sales' => 87, 'revenue' => '$69,600'],
            ['name' => 'Gaming Mouse', 'sales' => 76, 'revenue' => '$7,600'],
            ['name' => 'Mechanical Keyboard', 'sales' => 65, 'revenue' => '$9,750']
        ];
    }

    // Chart data (hardcoded)
    public function getChartDataProperty(): array
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'revenue' => [45000, 52000, 48000, 61000, 55000, 67000],
            'users' => [1200, 1350, 1100, 1500, 1300, 1600]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
