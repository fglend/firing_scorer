<?php

namespace App\Filament\Resources\ShootingSessions\RelationManagers;

use App\Filament\Resources\ShootingSessions\ShootingSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SessionRelationManager extends RelationManager
{
    protected static string $relationship = 'iotReadings';

    protected static ?string $title = 'IoT Readings';

    protected static ?string $relatedResource = ShootingSessionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shooting_session_id')
                    ->searchable(),
                TextColumn::make('device_id')
                    ->searchable(),
                TextColumn::make('captured_at')
                    ->searchable(),
                TextColumn::make('distance_m')
                    ->searchable(),
                TextColumn::make('temperature_c')
                    ->searchable(),
                TextColumn::make('humidity_percent')
                    ->searchable(),
                TextColumn::make('light_lux')
                    ->searchable(),
                TextColumn::make('imu_json')
                    ->searchable(),
                TextColumn::make('raw_payload')
                    ->searchable(),

            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
