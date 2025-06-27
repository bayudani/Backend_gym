<?php

namespace App\Filament\Resources\DailyPlayerResource\Pages;

use App\Filament\Resources\DailyPlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyPlayer extends EditRecord
{
    protected static string $resource = DailyPlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
