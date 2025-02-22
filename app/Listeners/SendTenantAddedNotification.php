<?php

namespace App\Listeners;

use App\Events\TenantAdded;
use App\Models\Tenant;
use App\Notifications\AdminEmailVerifyNotification;
use App\Notifications\UserEmailVerifyNotification;
use App\Notifications\TenantEmailVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTenantAddedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TenantAdded $event)
    {
        $tenant = Tenant::find($event->data->id);
        $tenant->notify(new TenantEmailVerifyNotification($event->data));
    }
}
