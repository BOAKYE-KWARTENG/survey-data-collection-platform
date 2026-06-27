<?php

namespace App\Filament\Actions;

use App\Models\SubmissionComment;
use App\Models\WorkflowStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;

class ResubmitAction
{
    public static function make(): Action
    {
        return Action::make('resubmit')
            ->label('Resubmit')
            ->icon('heroicon-o-paper-airplane')
            ->color('success')
            ->form([
                Textarea::make('comment')
                    ->label('Correction Note')
                    ->helperText('Briefly describe what was corrected.')
                    ->required(),
            ])
            ->action(function (array $data, $record): void {
                // Save the correction comment
                SubmissionComment::create([
                    'submission_id' => $record->id,
                    'user_id'       => auth()->id(),
                    'comment'       => $data['comment'],
                ]);

                // Move latest QA assignment back to pending
                $assignment = $record->latestQaAssignment;
                if ($assignment) {
                    $assignment->update(['status' => 'pending']);
                }

                // Move submission back to Submitted status
                $status = WorkflowStatus::where('name', 'Submitted')->first();
                if ($status) {
                    $record->transitionTo($status);
                }
            })
            ->visible(function ($record): bool {
                $user = auth()->user();

                if (!$user->hasRole('enumerator')) {
                    return false;
                }

                // Only show on rejected submissions
                // that belong to this enumerator
                return $record->enumerator_id === $user->id
                    && $record?->workflowStatus?->name === 'Rejected';
            })
            ->requiresConfirmation()
            ->modalHeading('Resubmit Corrected Submission')
            ->modalDescription('This submission will be sent back to QA for review.')
            ->modalSubmitActionLabel('Resubmit');
    }
}