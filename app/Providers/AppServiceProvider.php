<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\SubmissionAssignedToQa;
use App\Events\SubmissionReviewed;
use App\Events\SubmissionApproved;
use App\Listeners\NotifyQaOfficerOnAssignment;
use App\Listeners\NotifyEnumeratorOnReview;
use App\Listeners\NotifySupervisorOnApproval;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(SubmissionAssignedToQa::class, NotifyQaOfficerOnAssignment::class);
        Event::listen(SubmissionReviewed::class, NotifyEnumeratorOnReview::class);
        Event::listen(SubmissionApproved::class, NotifySupervisorOnApproval::class);
    }
}
