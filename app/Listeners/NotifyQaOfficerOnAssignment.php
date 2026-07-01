<?php

namespace App\Listeners;

use App\Events\SubmissionAssignedToQa;
use App\Notifications\QaAssignmentNotification;
use App\Models\User;

class NotifyQaOfficerOnAssignment
{
    public function handle(SubmissionAssignedToQa $event): void
    {
        $qaOfficer = User::find($event->assignment->qa_officer_id);

        if ($qaOfficer) {
            $qaOfficer->notify(new QaAssignmentNotification($event->assignment));
        }
    }
}