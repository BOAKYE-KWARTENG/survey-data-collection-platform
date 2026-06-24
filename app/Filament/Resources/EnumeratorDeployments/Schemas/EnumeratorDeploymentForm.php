<?php

namespace App\Filament\Resources\EnumeratorDeployments\Schemas;

use App\Models\District;
use App\Models\SurveyCampaign;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EnumeratorDeploymentForm
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
                    ->required(),
                Select::make('enumerator_id')
                    ->label('Enumerator')
                    ->options(
                        User::role('enumerator')->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->options([
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}