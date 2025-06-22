<?php

namespace App\Filament\Resources\ItemRewardsResource\Pages;

use App\Filament\Resources\ItemRewardsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemRewards extends ListRecords
{
    protected static string $resource = ItemRewardsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
