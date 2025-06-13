<?php

namespace App\Filament\Widgets;

use App\Models\MemberProfile;
use App\Models\Absen;
use App\Models\member;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Member
        $totalMembers = member::count();

        // Absen hari ini
        $absenToday = Absen::whereDate('scan_time', Carbon::today())->count();

        // Transaksi bulan ini
        $transactionThisMonth = Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        // Member aktif
        $activeMembers = member::where('is_active', true)->count();

        // Member nonaktif
        $inactiveMembers = member::where('is_active', false)->count();

        return [
            Stat::make('Total Member', $totalMembers)
                ->description('Semua member terdaftar')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Absen Hari Ini', $absenToday)
                ->description('Total absen hari ini')
                ->icon('heroicon-o-calendar')
                ->color('success'),

            Stat::make('Transaksi Bulan Ini', $transactionThisMonth)
                ->description('Total transaksi bulan ini')
                ->icon('heroicon-o-credit-card')
                ->color('warning'),

            Stat::make('Member Aktif', $activeMembers)
                ->description('Member yang aktif sekarang')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Member Nonaktif', $inactiveMembers)
                ->description('Member yang belum aktif')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
