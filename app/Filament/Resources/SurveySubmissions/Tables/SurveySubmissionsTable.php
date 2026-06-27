<?php

namespace App\Filament\Resources\SurveySubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use App\Filament\Actions\QaReviewAction;
use App\Filament\Actions\TransitionStatusAction;
use App\Filament\Actions\AssignToQaAction;
use App\Filament\Actions\ResubmitAction;


class SurveySubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('household.household_code')
                    ->label('Household Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('household.district.name')
                    ->label('District')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('household.district.region.name')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('enumerator.name')
                    ->label('Enumerator')
                    ->searchable(),
                TextColumn::make('workflowStatus.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record?->workflowStatus?->color ?? 'gray'),
                TextColumn::make('submitted_at')
                    ->label('Submitted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('workflow_status_id')
                    ->label('Status')
                    ->relationship('workflowStatus', 'name'),
                SelectFilter::make('campaign_id')
                    ->label('Campaign')
                    ->relationship('campaign', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                AssignToQaAction::make(),
                QaReviewAction::make(),
                ResubmitAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}