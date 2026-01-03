<?php

namespace App\Filament\Resources\ShootingSessions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShootingSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trainee_name'),
                DateTimePicker::make('session_date'),
                TextInput::make('total_score')
                    ->numeric(),
                TextInput::make('average_score')
                    ->numeric(),
            ]);
    }
}
