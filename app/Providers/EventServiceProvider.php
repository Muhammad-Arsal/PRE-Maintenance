<?php

namespace App\Providers;

use App\Events\AdminAdded;
use App\Events\ResendUserVerification;
use App\Events\UserAdded;
use App\Events\TenantAdded;
use App\Events\LandlordAdded;
use App\Events\ContractorAdded;
use App\Listeners\ResendUserVerfiicationNotification;
use App\Listeners\SendAdminAddedNotification;
use App\Listeners\SendTenantAddedNotification;
use App\Listeners\SendUserAddedNotification;
use App\Listeners\SendLandlordAddedNotification;
use App\Listeners\SendContractorAddedNotification;
use App\Notifications\ResendUserEmailVerifyNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserAdded::class => [
            SendUserAddedNotification::class,
        ],
        AdminAdded::class => [
            SendAdminAddedNotification::class,
        ],
        TenantAdded::class => [
            SendTenantAddedNotification::class,
        ],
        LandlordAdded::class => [
            SendLandlordAddedNotification::class,
        ],
        ContractorAdded::class => [
            SendContractorAddedNotification::class,
        ],
        ResendUserVerification::class => [
            ResendUserVerfiicationNotification::class
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
