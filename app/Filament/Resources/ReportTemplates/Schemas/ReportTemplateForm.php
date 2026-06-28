<?php

namespace App\Filament\Resources\ReportTemplates\Schemas;

use App\Models\ReportTemplate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReportTemplateForm
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
                Select::make('type')
                    ->options([
                        ReportTemplate::DISTRICT_COVERAGE      => 'District Coverage',
                        ReportTemplate::ENUMERATOR_PERFORMANCE => 'Enumerator Performance',
                        ReportTemplate::QA_PERFORMANCE         => 'QA Performance',
                        ReportTemplate::FINANCIAL_INCLUSION    => 'Financial Inclusion',
                        ReportTemplate::GENDER_REPORT          => 'Gender Report',
                        ReportTemplate::EMPLOYMENT_REPORT      => 'Employment Report',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}