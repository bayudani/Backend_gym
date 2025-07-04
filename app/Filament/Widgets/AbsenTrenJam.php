<?php

namespace App\Filament\Widgets;

use App\Models\Absen;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AbsenTrenJam extends ChartWidget
{
    protected static ?string $heading = '⏰ Tren Jam Absen Member';

    protected function getData(): array
    {
        $data = Absen::selectRaw('HOUR(scan_time) as jam, COUNT(*) as total')
            ->groupBy('jam')
            ->orderBy('jam')
            ->pluck('total', 'jam');

        $labels = [];
        $values = [];

        for ($i = 5; $i <= 22; $i++) {
            $labels[] = sprintf('%02d:00', $i);
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Absen',
                    'data' => $values,
                    'backgroundColor' => '#4F46E5', // indigo-600
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected static ?int $sort = 1; // urutan widget di dashboard
}
