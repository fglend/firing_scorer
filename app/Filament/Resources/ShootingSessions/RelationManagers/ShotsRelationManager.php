<?php

namespace App\Filament\Resources\ShootingSessions\RelationManagers;

use App\Filament\Resources\ShootingSessions\ShootingSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShotsRelationManager extends RelationManager
{
    protected static string $relationship = 'shots';

    protected static ?string $title = 'Shots';

    protected static ?string $relatedResource = ShootingSessionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('target.target_type')
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
