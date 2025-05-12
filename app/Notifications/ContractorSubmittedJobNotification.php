<?php
namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContractorSubmittedJobNotification extends Notification implements ShouldQueue
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
            ->where('type', 'Contractor Job Submission')
            ->first();

        if ($template) {
            return (new MailMessage)
                ->view('admin.emails.contractor_submitted', [
                    'email' => $notifiable->email,
                    'name' => $notifiable->name,
                    'template' => $template,
                    'link' => $this->notification_detail["route"],
                ])
                ->subject($template->subject);
        }

        return (new MailMessage)->line('A contractor has submitted job details.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'notification_detail' => $this->notification_detail,
        ];
    }
}

?>