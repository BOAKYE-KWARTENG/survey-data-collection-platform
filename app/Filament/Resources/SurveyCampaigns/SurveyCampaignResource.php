<?php

namespace App\Filament\Resources\SurveyCampaigns;

use App\Filament\Resources\SurveyCampaigns\Pages\CreateSurveyCampaign;
use App\Filament\Resources\SurveyCampaigns\Pages\EditSurveyCampaign;
use App\Filament\Resources\SurveyCampaigns\Pages\ListSurveyCampaigns;
use App\Filament\Resources\SurveyCampaigns\Schemas\SurveyCampaignForm;
use App\Filament\Resources\SurveyCampaigns\Tables\SurveyCampaignsTable;
use App\Models\SurveyCampaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyCampaignResource extends Resource
{
    protected static ?string $model = SurveyCampaign::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Survey Management';
    }

    public static function form(Schema $schema): Schema
    {
        return SurveyCampaignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyCampaignsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveyCampaigns::route('/'),
            'create' => CreateSurveyCampaign::route('/create'),
            'edit' => EditSurveyCampaign::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin', 'supervisor']);
    }
}
