<?php

namespace App\Listeners;

use App\Events\SubmissionApproved;
use App\Notifications\SubmissionApprovedNotification;
use App\Models\User;

class NotifySupervisorOnApproval
{
    public function handle(SubmissionApproved $event): void
    {
        // Notify all supervisors
        User::role('supervisor')->each(function ($supervisor) use ($event) {
            $supervisor->notify(
                new SubmissionApprovedNotification($event->submission)
            );
        });
    }
}