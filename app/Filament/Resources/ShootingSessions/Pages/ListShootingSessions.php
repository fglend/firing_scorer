<?php

namespace App\Filament\Resources\ShootingSessions\Pages;

use App\Filament\Resources\ShootingSessions\ShootingSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShootingSessions extends ListRecords
{
    protected static string $resource = ShootingSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
