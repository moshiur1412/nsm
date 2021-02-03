<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'App\Events\UserPasswordChanged' => [
            'App\Listeners\SendEmailAfterPasswordChanged',
        ],

        'App\Events\UserVerified' => [
            'App\Listeners\SendEmailForUserVerified',
        ],

        'App\Events\SubsidyAppCompleted' => [
            'App\Listeners\SendEmailAfterSubsidyAppCompleted',
        ],

        'App\Events\AwardAppCompleted' => [
            'App\Listeners\SendEmailAfterAwardAppCompleted',
        ],

        //backend "subsidyApplications - 不採択を通知"用Listeners"
        'App\Events\SubsidyRejectionNoticed' => [
            'App\Listeners\SendEmailSubsidyRejectionNoticed',
        ],

        //backend "awardApplications - 不採択を通知"用Listeners"
        'App\Events\AwardRejectionNoticed' => [
            'App\Listeners\SendEmailAwardRejectionNoticed',
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
