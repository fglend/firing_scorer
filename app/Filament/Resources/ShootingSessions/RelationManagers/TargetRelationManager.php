<?php

namespace App\Filament\Resources\ShootingSessions\RelationManagers;

use App\Filament\Resources\ShootingSessions\ShootingSessionResource;
use Dom\Text;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TargetRelationManager extends RelationManager
{
    protected static string $relationship = 'target';

    protected static ?string $title = 'Target';

    protected static ?string $relatedResource = ShootingSessionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('target_type')
                    ->searchable(),
                TextColumn::make('ring_10_radius')
                    ->searchable(),
                TextColumn::make('ring_9_radius')
                    ->searchable(),
                TextColumn::make('ring_8_radius')
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
