<?php

namespace App\Filament\Resources\AbsensResource\Pages;

use App\Filament\Resources\AbsensResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbsens extends EditRecord
{
    protected static string $resource = AbsensResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
