<?php

namespace App\Filament\Resources\Households\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use App\Models\Community;
use App\Models\District;
use App\Models\SurveyCampaign;

use Filament\Forms\Components\Placeholder;


/* class HouseholdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->relationship('campaign', 'name')
                    ->required(),
                Select::make('district_id')
                    ->relationship('district', 'name')
                    ->required(),
                Select::make('community_id')
                    ->relationship('community', 'name'),
                TextInput::make('household_code')
                    ->required(),
                TextInput::make('gps_latitude')
                    ->numeric(),
                TextInput::make('gps_longitude')
                    ->numeric(),
                TextInput::make('registered_by')
                    ->required()
                    ->numeric(),
            ]);
    }
} */


class HouseholdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->label('Campaign')
                    ->options(SurveyCampaign::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('district_id')
                    ->label('District')
                    ->options(District::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('community_id', null)),
                Select::make('community_id')
                    ->label('Community')
                    ->options(function (callable $get) {
                        $districtId = $get('district_id');
                        if (!$districtId) return [];
                        return Community::where('district_id', $districtId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->nullable(),
                TextInput::make('household_code')
                    ->label('Household Code')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Auto-generated on save'),
                TextInput::make('gps_latitude')
                    ->label('GPS Latitude')
                    ->numeric()
                    ->nullable(),
                TextInput::make('gps_longitude')
                    ->label('GPS Longitude')
                    ->numeric()
                    ->nullable(),
            ]);
    }
}