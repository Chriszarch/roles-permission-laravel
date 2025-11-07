<?php

namespace App\Livewire;

use Livewire\Component;
use Mary\Traits\Toast;
use App\Models\QrCode;

class Dashboard extends Component
{
    use Toast;

    public string $title = 'Dashboard';

    // Dashboard stats (hardcoded)
    public function getStatsProperty(): array
    {
        $totalQr = QrCode::where('is_active', 1)
            ->count();
        return [
            [
                'title' => 'Total de QRs',
                'value' => $totalQr,
                'change' => '+12%',
                'icon' => 'o-qr-code',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
                'positive' => true
            ],
            [
                'title' => 'Total Escaneos',
                'value' => '100',
                'change' => '+8.3%',
                'icon' => 'o-arrow-trending-up',
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
                'positive' => true
            ],
            [
                'title' => 'Promedio Diario',
                'value' => '12',
                'change' => '+1.2%',
                'icon' => 'o-calendar-days',
                'color' => 'text-amber-400',
                'bg' => 'bg-amber-50',
                'positive' => true
            ],
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
