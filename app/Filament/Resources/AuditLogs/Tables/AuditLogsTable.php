<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Action')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => $state
                        ? class_basename($state)
                        : '-'
                    )
                    ->sortable(),
                TextColumn::make('subject_id')
                    ->label('Record ID')
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label('Performed By')
                    ->searchable()
                    ->default('System'),
                TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '-';
                        $props = is_array($state) ? $state : json_decode($state, true);
                        $old = $props['old'] ?? [];
                        $new = $props['attributes'] ?? [];
                        if (empty($old) && empty($new)) return '-';
                        $changes = [];
                        foreach ($new as $key => $value) {
                            $from = $old[$key] ?? 'null';
                            $changes[] = "{$key}: {$from} → {$value}";
                        }
                        return implode(', ', $changes);
                    })
                    ->wrap()
                    ->limit(80),
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('subject_type')
                    ->label('Model')
                    ->options([
                        'App\Models\SurveySubmission' => 'Survey Submission',
                        'App\Models\Household'        => 'Household',
                        'App\Models\QaAssignment'     => 'QA Assignment',
                        'App\Models\QaReview'         => 'QA Review',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}