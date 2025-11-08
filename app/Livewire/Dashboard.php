<?php

namespace App\Livewire;

use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    public string $title = 'Dashboard';

    // Período de tiempo seleccionado (default: 'today')
    public string $selectedPeriod = 'today';

    /**
     * Períodos de tiempo disponibles para el filtro
     * La clave es el identificador y el valor es la etiqueta en español
     */
    public function getPeriodsProperty(): array
    {
        return [
            'today' => 'Hoy',
            '7days' => '7 días',
            '15days' => '15 días',
            '30days' => '30 días',
        ];
    }

    /**
     * Obtiene la fecha inicial según el período seleccionado
     */
    protected function getStartDate(): Carbon
    {
        return match ($this->selectedPeriod) {
            'today' => now()->startOfDay(),
            '7days' => now()->subDays(7)->startOfDay(),
            '15days' => now()->subDays(15)->startOfDay(),
            '30days' => now()->subDays(30)->startOfDay(),
            default => now()->startOfDay(),
        };
    }

    /**
     * Calcula el cambio porcentual entre dos valores
     */
    protected function calculateChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'value' => $current > 0 ? '+100%' : '0%',
                'positive' => $current > 0,
            ];
        }

        $change = (($current - $previous) / $previous) * 100;

        return [
            'value' => ($change >= 0 ? '+' : '').number_format($change, 1).'%',
            'positive' => $change >= 0,
        ];
    }

    /**
     * Obtiene las estadísticas del dashboard según el período seleccionado
     */
    public function getStatsProperty(): array
    {
        $startDate = $this->getStartDate();
        $previousStartDate = clone $startDate;
        $previousStartDate->subDays($startDate->diffInDays(now()));

        // Métricas actuales
        $totalQr = QrCode::where('is_active', 1)->count();
        $totalScans = DB::table('qr_scans')
            ->where('scanned_at', '>=', $startDate)
            ->count();

        // Calcular el promedio según el período
        $avgScans = match ($this->selectedPeriod) {
            'today' => $totalScans, // Si es hoy, el promedio es el total
            default => DB::table('qr_scans')
                ->where('scanned_at', '>=', $startDate)
                ->selectRaw('
                    CASE 
                        WHEN DATEDIFF(NOW(), ?) = 0 THEN COUNT(*)
                        ELSE ROUND(COUNT(*) / DATEDIFF(NOW(), ?), 0)
                    END as avg
                ', [$startDate, $startDate])
                ->value('avg') ?? 0
        };

        // Métricas del período anterior para comparación
        $previousScans = DB::table('qr_scans')
            ->whereBetween('scanned_at', [$previousStartDate, $startDate])
            ->count();
        // Calcular el promedio del período anterior
        $previousAvg = match ($this->selectedPeriod) {
            'today' => $previousScans, // Si es hoy, el promedio es el total
            default => DB::table('qr_scans')
                ->whereBetween('scanned_at', [$previousStartDate, $startDate])
                ->selectRaw('
                    CASE 
                        WHEN DATEDIFF(?, ?) = 0 THEN COUNT(*)
                        ELSE ROUND(COUNT(*) / DATEDIFF(?, ?), 0)
                    END as avg
                ', [$startDate, $previousStartDate, $startDate, $previousStartDate])
                ->value('avg') ?? 0
        };

        // Calcular cambios
        $scansChange = $this->calculateChange($totalScans, $previousScans);
        $avgChange = $this->calculateChange($avgScans, $previousAvg);

        return [
            [
                'title' => 'Total de QRs',
                'value' => $totalQr,
                'change' => '+0%', // Los QRs activos no tienen comparación temporal
                'icon' => 'o-qr-code',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
                'positive' => true,
            ],
            [
                'title' => 'Total Escaneos',
                'value' => $totalScans,
                'change' => $scansChange['value'],
                'icon' => 'o-arrow-trending-up',
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
                'positive' => $scansChange['positive'],
            ],
            [
                'title' => 'Promedio Diario',
                'value' => $avgScans,
                'change' => $avgChange['value'],
                'icon' => 'o-calendar-days',
                'color' => 'text-amber-400',
                'bg' => 'bg-amber-50',
                'positive' => $avgChange['positive'],
            ],
        ];
    }

    /**
     * Obtiene los datos del gráfico según el período seleccionado
     */
    public function getChartDataProperty(): array
    {
        $startDate = $this->getStartDate();

        // Determinar el formato de fecha según el período
        $groupFormat = match ($this->selectedPeriod) {
            'today' => '%H:00', // Por hora
            default => '%Y-%m-%d', // Por día
        };

        // Obtener datos agrupados
        $scans = DB::table('qr_scans')
            ->where('scanned_at', '>=', $startDate)
            ->selectRaw("
                DATE_FORMAT(scanned_at, '{$groupFormat}') as label,
                COUNT(*) as total
            ")
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // Preparar datos para el gráfico
        return [
            'labels' => $scans->pluck('label')->toArray(),
            'datasets' => [
                [
                    'name' => 'Escaneos',
                    'data' => $scans->pluck('total')->toArray(),
                ],
            ],
        ];
    }

    /**
     * Método para actualizar el período seleccionado
     */
    public function updatePeriod(string $period): void
    {
        $this->selectedPeriod = $period;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
