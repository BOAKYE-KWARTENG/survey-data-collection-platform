<?php

namespace App\Filament\Resources\Households\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

    
class HouseholdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('household_code')
                    ->label('Household Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('district.region.name')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('district.name')
                    ->label('District')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('community.name')
                    ->label('Community')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label('Submissions'),
                    
                TextColumn::make('registeredBy.name')
                    ->label('Registered By')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn () => !auth()->user()->hasRole('enumerator'))
                ,
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => !auth()->user()->hasRole('enumerator'))
                    ,
                ]),
            ]);
    }
}