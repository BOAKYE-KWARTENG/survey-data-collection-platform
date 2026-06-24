<?php

namespace App\Filament\Resources\SurveyCampaigns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveyCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required()
                    ->after('start_date'),
                Select::make('status')
                    ->options([
                        'draft'    => 'Draft',
                        'active'   => 'Active',
                        'closed'   => 'Closed',
                        'archived' => 'Archived',
                    ])
                    ->default('draft')
                    ->required(),
            ]);
    }
}