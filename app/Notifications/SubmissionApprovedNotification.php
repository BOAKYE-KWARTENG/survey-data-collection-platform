<?php

namespace App\Notifications;

use App\Models\SurveySubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SubmissionApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SurveySubmission $submission
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'         => 'Submission Approved',
            'message'       => "Submission {$this->submission->household->household_code} has been approved and is ready for publishing.",
            'submission_id' => $this->submission->id,
            'url'           => '/admin/survey-submissions/' . $this->submission->id,
        ];
    }
}