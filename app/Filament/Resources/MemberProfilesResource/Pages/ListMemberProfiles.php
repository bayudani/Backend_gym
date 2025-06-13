<?php

namespace App\Filament\Resources\MemberProfilesResource\Pages;

use App\Filament\Resources\MemberProfilesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberProfiles extends ListRecords
{
    protected static string $resource = MemberProfilesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
