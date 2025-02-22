<?php

namespace App\Listeners;

use App\Events\ContractorAdded;
use App\Models\Contractor;
use App\Notifications\ContractorEmailVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendContractorAddedNotification
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
    public function handle(ContractorAdded $event)
    {
        $contractor = Contractor::find($event->data->id);
        $contractor->notify(new ContractorEmailVerifyNotification($event->data));
    }
}
