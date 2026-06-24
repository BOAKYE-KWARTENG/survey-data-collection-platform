<?php

namespace App\Filament\Resources\SurveySubmissions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveySubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->relationship('campaign', 'name')
                    ->required(),
                Select::make('household_id')
                    ->relationship('household', 'id')
                    ->required(),
                Select::make('enumerator_id')
                    ->relationship('enumerator', 'name')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('submitted_at'),
            ]);
    }
}
