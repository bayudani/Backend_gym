<?php

namespace App\Filament\Resources\MembershipsResource\Pages;

use App\Filament\Resources\MembershipsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberships extends EditRecord
{
    protected static string $resource = MembershipsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
