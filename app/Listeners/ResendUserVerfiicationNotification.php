<?php

namespace App\Listeners;

use App\Events\ResendUserVerification;
use App\Models\User;
use App\Notifications\ResendUserEmailVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ResendUserVerfiicationNotification
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
    public function handle(ResendUserVerification $event)
    {
        $user = User::find($event->data->id);

        $user->notify(new ResendUserEmailVerifyNotification($event->data));
    }
}
