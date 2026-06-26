<?php

namespace App\Filament\Resources\WorkflowStatuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkflowStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('color')
                    ->options([
                        'gray'    => 'Gray',
                        'info'    => 'Blue',
                        'warning' => 'Yellow',
                        'primary' => 'Primary',
                        'danger'  => 'Red',
                        'success' => 'Green',
                    ])
                    ->default('gray')
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_default')
                    ->label('Default Status')
                    ->default(false),
                Toggle::make('is_final')
                    ->label('Final Status')
                    ->default(false),
            ]);
    }
}
