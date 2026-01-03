<?php

namespace App\Filament\Resources\ShootingSessions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShootingSessionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('trainee_name')
                    ->placeholder('-'),
                TextEntry::make('session_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('total_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('average_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
