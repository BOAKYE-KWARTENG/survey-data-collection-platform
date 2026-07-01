<?php

namespace App\Notifications;

use App\Models\QaAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class QaAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public QaAssignment $assignment
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $submission = $this->assignment->submission;
        $assignedBy = $this->assignment->assignedBy;

        return [
            'title'         => 'New QA Assignment',
            'message'       => "You have been assigned submission {$submission->household->household_code} for review by {$assignedBy->name}.",
            'submission_id' => $submission->id,
            'url'           => '/admin/survey-submissions/' . $submission->id,
        ];
    }
}