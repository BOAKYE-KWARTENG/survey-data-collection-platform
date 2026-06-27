<?php

namespace App\Filament\Actions;

use App\Models\QaReview;
use App\Models\WorkflowStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class QaReviewAction
{
    public static function make(): Action
    {
        return Action::make('qaReview')
            ->label('Review')
            ->icon('heroicon-o-clipboard-document-check')
            ->color('primary')
            ->form([
                Select::make('decision')
                    ->label('Decision')
                    ->options([
                        QaReview::APPROVED            => 'Approve',
                        QaReview::REJECTED            => 'Reject',
                        QaReview::NEEDS_CLARIFICATION => 'Needs Clarification',
                    ])
                    ->required()
                    ->live(),
                Textarea::make('comments')
                    ->label('Comments')
                    ->helperText('Required if rejecting or requesting clarification.')
                    ->nullable(),
            ])
            ->action(function (array $data, $record): void {
                $assignment = $record->latestQaAssignment;

                // Create the QA review record
                QaReview::create([
                    'submission_id'   => $record->id,
                    'qa_assignment_id'=> $assignment->id,
                    'qa_officer_id'   => auth()->id(),
                    'decision'        => $data['decision'],
                    'comments'        => $data['comments'] ?? null,
                    'reviewed_at'     => now(),
                ]);

                // Update assignment status
                $assignment->update(['status' => $data['decision']]);

                // Move submission to correct workflow status
                $statusName = match ($data['decision']) {
                    QaReview::APPROVED            => 'Approved',
                    QaReview::REJECTED            => 'Rejected',
                    QaReview::NEEDS_CLARIFICATION => 'QA Review',
                    default                       => 'QA Review',
                };

                $status = WorkflowStatus::where('name', $statusName)->first();
                if ($status) {
                    $record->transitionTo($status);
                }
            })
            ->visible(function ($record): bool {
                $user = auth()->user();

                if (!$user->hasRole('qa_officer')) {
                    return false;
                }

                // Only show if this submission is assigned to this QA officer
                $assignment = $record->latestQaAssignment;

                if (!$assignment) {
                    return false;
                }

                return $assignment->qa_officer_id === $user->id
                    && $assignment->status === 'pending';
            })
            ->requiresConfirmation()
            ->modalHeading('Review Submission')
            ->modalDescription('Your decision will be recorded and the submission will be updated accordingly.')
            ->modalSubmitActionLabel('Submit Review');
    }
}