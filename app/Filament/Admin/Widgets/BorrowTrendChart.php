<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Borrow;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class BorrowTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trafik Peminjaman';
    protected static ?int $sort = 2;
    protected static string $color = 'primary';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Karena kita mungkin tidak punya library Trend, kita pakai query manual yang kompatibel
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = Borrow::whereDate('borrow_date', $date->toDateString())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Buku Dipinjam',
                    'data' => $data,
                    'fill' => 'start',
                    'tension' => 0.4,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
