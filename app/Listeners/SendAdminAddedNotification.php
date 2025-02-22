<?php

namespace App\Listeners;

use App\Events\AdminAdded;
use App\Models\Admin;
use App\Notifications\AdminEmailVerifyNotification;
use App\Notifications\UserEmailVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAdminAddedNotification
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
    public function handle(AdminAdded $event)
    {
        $admin = Admin::find($event->data->id);
        $admin->notify(new AdminEmailVerifyNotification($event->data));
    }
}
