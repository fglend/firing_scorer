<?php

namespace App\Filament\Resources\ShootingSessions;

use App\Filament\Resources\ShootingSessions\Pages\CreateShootingSession;
use App\Filament\Resources\ShootingSessions\Pages\EditShootingSession;
use App\Filament\Resources\ShootingSessions\Pages\ListShootingSessions;
use App\Filament\Resources\ShootingSessions\Pages\ViewShootingSession;
use App\Filament\Resources\ShootingSessions\RelationManagers\RecommendationsRelationManager;
use App\Filament\Resources\ShootingSessions\RelationManagers\SessionRelationManager;
use App\Filament\Resources\ShootingSessions\RelationManagers\ShotsRelationManager;
use App\Filament\Resources\ShootingSessions\RelationManagers\TargetRelationManager;
use App\Filament\Resources\ShootingSessions\Schemas\ShootingSessionForm;
use App\Filament\Resources\ShootingSessions\Schemas\ShootingSessionInfolist;
use App\Filament\Resources\ShootingSessions\Tables\ShootingSessionsTable;
use App\Models\ShootingSession;
use App\Models\Target;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShootingSessionResource extends Resource
{
    protected static ?string $model = ShootingSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ShootingSession';

    public static function form(Schema $schema): Schema
    {
        return ShootingSessionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShootingSessionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShootingSessionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SessionRelationManager::class,
            RecommendationsRelationManager::class,
            ShotsRelationManager::class,
            TargetRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShootingSessions::route('/'),
            'create' => CreateShootingSession::route('/create'),
            'view' => ViewShootingSession::route('/{record}'),
            'edit' => EditShootingSession::route('/{record}/edit'),
        ];
    }
}
