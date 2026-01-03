<?php

namespace App\Filament\Resources\ShootingSessions\RelationManagers;

use App\Filament\Resources\ShootingSessions\ShootingSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecommendationsRelationManager extends RelationManager
{
    protected static string $relationship = 'recommendations';

    protected static ?string $title = 'Recommendations';

    protected static ?string $relatedResource = ShootingSessionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('recommendation_type')
                    ->searchable(),
                TextColumn::make('message')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
