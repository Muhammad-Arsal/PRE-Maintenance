<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JobAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $notification_detail;

    public function __construct($notification_detail)
    {
        $this->notification_detail = $notification_detail;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $template = EmailTemplate::where('status', '1')
            ->where('type', 'Job Assigned Notification Mail')
            ->first();

        if ($template) {
            return (new MailMessage)
                ->view('admin.emails.job_assigned', [
                    'email' => $notifiable->email,
                    'name' => $notifiable->name,
                    'system_name' => config('app.name'),
                    'template' => $template,
                    'link' => $this->notification_detail['route'],
                ])
                ->subject($template->subject);
        }

        return (new MailMessage)->line('You have a new job assignment.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'notification_detail' => $this->notification_detail,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

?>