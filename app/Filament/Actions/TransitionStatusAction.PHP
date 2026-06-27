<?php

namespace App\Filament\Actions;

use App\Models\WorkflowStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class TransitionStatusAction
{
    public static function make(): Action
    {
        return Action::make('transitionStatus')
            ->label('Move Status')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->form([
                Select::make('workflow_status_id')
                    ->label('Select New Status')
                    ->options(
                        WorkflowStatus::orderBy('sort_order')
                            ->pluck('name', 'id')
                    )
                    ->required(),
            ])
            ->action(function (array $data, $record): void {
                $status = WorkflowStatus::find($data['workflow_status_id']);
                $record->transitionTo($status);
            })
            ->visible(fn () => auth()->user()->hasRole(['admin', 'supervisor']));
    }
}