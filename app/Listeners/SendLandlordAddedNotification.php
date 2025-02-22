<?php

namespace App\Listeners;

use App\Events\LandlordAdded;
use App\Models\Landlord;
use App\Notifications\LandlordEmailVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLandlordAddedNotification
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
    public function handle(LandlordAdded $event)
    {
        $landlord = Landlord::find($event->data->id);
        $landlord->notify(new LandlordEmailVerifyNotification($event->data));
    }
}
