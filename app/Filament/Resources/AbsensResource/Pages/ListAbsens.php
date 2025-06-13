<?php

namespace App\Filament\Resources\AbsensResource\Pages;

use App\Filament\Resources\AbsensResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAbsens extends ListRecords
{
    protected static string $resource = AbsensResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Action::make('scanQR')
            ->label('Scan QR')
            ->icon('heroicon-o-camera')
            ->url(route('absens.scan.page')),

        Action::make('inputManual')
            ->label('Input Manual')
            ->icon('heroicon-o-pencil')
            ->url(static::getResource()::getUrl('create')) // redirect ke halaman /create
            ->color('secondary'),
            // ->openUrlInNewTab(), // atau hapus kalau mau tetap di halaman yang sama
    ];
    }
}
