<?php

namespace App\Filament\Resources\MemberProfilesResource\Pages;

use App\Filament\Resources\MemberProfilesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberProfiles extends EditRecord
{
    protected static string $resource = MemberProfilesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
