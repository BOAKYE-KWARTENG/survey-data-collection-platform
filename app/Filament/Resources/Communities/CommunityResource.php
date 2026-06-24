<?php

namespace App\Filament\Resources\Communities;

use App\Filament\Resources\Communities\Pages\CreateCommunity;
use App\Filament\Resources\Communities\Pages\EditCommunity;
use App\Filament\Resources\Communities\Pages\ListCommunities;
use App\Filament\Resources\Communities\Schemas\CommunityForm;
use App\Filament\Resources\Communities\Tables\CommunitiesTable;
use App\Models\Community;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class CommunityResource extends Resource
{
    protected static ?string $model = Community::class;
    // protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Geographic Structure';
    }


    public static function form(Schema $schema): Schema
    {
        return CommunityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommunitiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommunities::route('/'),
            'create' => CreateCommunity::route('/create'),
            'edit' => EditCommunity::route('/{record}/edit'),
        ];
    }
}
