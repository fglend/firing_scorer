<?php

namespace App\Filament\Resources\ShootingSessions\Pages;

use App\Filament\Resources\ShootingSessions\ShootingSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditShootingSession extends EditRecord
{
    protected static string $resource = ShootingSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
