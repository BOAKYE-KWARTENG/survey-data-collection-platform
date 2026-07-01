<?php

namespace App\Notifications;

use App\Models\QaReview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SubmissionReviewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public QaReview $review
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $submission = $this->review->submission;
        $decision   = ucfirst($this->review->decision);
        $officer    = $this->review->qaOfficer;

        return [
            'title'         => "Submission {$decision}",
            'message'       => "Your submission {$submission->household->household_code} has been {$decision} by {$officer->name}." .
                ($this->review->comments ? " Comment: {$this->review->comments}" : ''),
            'submission_id' => $submission->id,
            'decision'      => $this->review->decision,
            'url'           => '/admin/survey-submissions/' . $submission->id,
        ];
    }
}