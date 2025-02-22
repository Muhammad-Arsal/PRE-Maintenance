<?php

namespace App\Listeners;

use App\Events\UserAdded;
use App\Models\User;
use App\Notifications\UserEmailVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserAddedNotification
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
    public function handle(UserAdded $event)
    {
        $user = User::find($event->data->id);
        $user->notify(new UserEmailVerifyNotification($event->data));
    }
}
