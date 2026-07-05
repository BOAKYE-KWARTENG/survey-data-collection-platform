<?php

namespace App\Filament\Actions;

use App\Models\QaAssignment;
use App\Models\User;
use App\Models\WorkflowStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use App\Events\SubmissionAssignedToQa;

class AssignToQaAction
{
    public static function make(): Action
    {
        return Action::make('assignToQa')
            ->label('Assign to QA')
            ->icon('heroicon-o-user-plus')
            ->color('warning')
            ->form([
                Select::make('qa_officer_id')
                    ->label('Select QA Officer')
                    ->options(
                        User::role('qa_officer')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),
                Textarea::make('note')
                    ->label('Note to QA Officer (Optional)')
                    ->nullable(),
            ])
            ->action(function (array $data, $record): void {
                // Create the QA assignment
                $assignment = QaAssignment::create([
                    'submission_id' => $record->id,
                    'qa_officer_id' => $data['qa_officer_id'],
                    'assigned_by'   => auth()->id(),
                    'assigned_at'   => now(),
                    'status'        => 'pending',
                ]);

                // Move submission to QA Assigned status
                $status = WorkflowStatus::where('name', 'QA Assigned')->first();
                if ($status) {
                    $record->transitionTo($status);
                }

                // Inside the action() callback after QaAssignment::create()
                event(new SubmissionAssignedToQa($assignment));

            })
            ->visible(function ($record): bool {
                $user = auth()->user();

                if (!$user->hasRole(['admin', 'supervisor'])) {
                    return false;
                }

                // Only show on submitted submissions
                // that are not already QA assigned or beyond
                $blockList = ['QA Assigned', 'QA Review', 'Approved', 'Published'];

                return !in_array(
                    $record?->workflowStatus?->name,
                    $blockList
                );
            })
            ->requiresConfirmation()
            ->modalHeading('Assign Submission to QA Officer')
            ->modalDescription('This submission will be sent to the selected QA Officer for review.')
            ->modalSubmitActionLabel('Assign');
    }
}