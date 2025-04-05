<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\EmailTemplate;
use App\Models\EventType;
use App\Models\Events;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminder;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    protected $signature = 'event-reminder:send-event-reminders';
    protected $description = 'Send event reminder emails 24 hours before and at 8 AM on the event date';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $start_time = microtime(true);

        info('Event Reminder: Running at ' . now()->format('Y-m-d h:i:s'));

        if (now()->format('H:i') == '08:00') {
            $this->sendRemindersAt8Am();
        } elseif (now()->format('H:i') == '13:00') {
            $this->sendRemindersBefore24Hours();
        }

        $seconds_taken = round(microtime(true) - $start_time, 2);
        if ($seconds_taken) {
            info('Event Reminder: reminder emails sent successfully in ' . $seconds_taken . ' seconds');
        }else {
            info('Event Reminder: No reminders found');
        }
    }

    protected function sendRemindersBefore24Hours()
    {
        $events = Events::whereBetween('date_from', [now()->addDay()->startOfDay(), now()->addDay()->endOfDay()])->with('eventType.emailTemplate')
            ->get();

        foreach ($events as $event) {
            $this->sendReminderEmails($event);
        }
    }

    protected function sendRemindersAt8Am()
    {
        $events = Events::whereDate('date_from', '=', now()->toDateString())->with('eventType.emailTemplate')
            ->get();

        foreach ($events as $event) {
            $this->sendReminderEmails($event);
        }
    }

    protected function sendReminderEmails($event)
    {
        $platformUsers = $event->eventUsers; //bring list of platform users (admin users)

        // $template = EmailTemplate::where('status', '1')->where('type', 'Event Reminder')->first();

        $event_type = !empty($event->eventType) ? $event->eventType : '';

        $template = !empty($event_type) ? $event_type->emailTemplate : EmailTemplate::where('status', '1')->where('type', 'Event Reminder')->first();

        foreach ($platformUsers as $user) {
            $platform_user = Admin::find($user->id);
            if ($platform_user && $platform_user->email) {

                $e_date_from = Carbon::parse($event->date_from)->format('d/m/Y H:i');
                $e_date_to = Carbon::parse($event->date_to)->format('d/m/Y H:i');
                $data = array(
                    'user_name' => $platform_user->name,
                    'event_type' => !empty($event_type) ? $event_type->event_name : 'Default',
                    'date' => $e_date_from . ' -- ' . $e_date_to,
                    'description' => $event->description,
                    'template' => $template->content,
                    'type' => 'internal_email',
                );

                
                Mail::to($platform_user->email)->send(new EventReminder($data));
                info('Event Reminder: reminder email sent successfully to platform user '. $platform_user->email);
            }
        }

        // Send email to external user
        if (isset($event->external_user) && $event->external_user) {
            $e_date_from = Carbon::parse($event->date_from)->format('d/m/Y H:i');
            $e_date_to = Carbon::parse($event->date_to)->format('d/m/Y H:i');
            $external_user_data = array(
                'user_name' => $event->external_user_name ? $event->external_user_name : $event->external_user, // For external user we don't have a user so we use its own email
                'event_type' => $event_type->event_name,
                'date' => $e_date_from . ' -- ' . $e_date_to,
                'description' => $event->description,
                'template' => $template->content,
                'type' => 'external_email',
            );

            Mail::to($event->external_user)->send(new EventReminder($external_user_data));
            info('Event Reminder: reminder email sent successfully to external user '. $event->external_user);
        }
    }
}
