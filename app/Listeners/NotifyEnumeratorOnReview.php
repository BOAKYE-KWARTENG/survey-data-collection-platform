<?php

namespace App\Listeners;

use App\Events\SubmissionReviewed;
use App\Notifications\SubmissionReviewedNotification;
use App\Models\User;

class NotifyEnumeratorOnReview
{
    public function handle(SubmissionReviewed $event): void
    {
        $enumerator = User::find($event->review->submission->enumerator_id);

        if ($enumerator) {
            $enumerator->notify(
                new SubmissionReviewedNotification($event->review)
            );
        }
    }
}