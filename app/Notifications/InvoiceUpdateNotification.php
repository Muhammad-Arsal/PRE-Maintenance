<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceUpdateNotification extends Notification
{
    protected $notification_detail;
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification_detail)
    {
        $this->notification_detail = $notification_detail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $template =  EmailTemplate::where('status','1')->where('type','Invoice Status Change')->first();

        if($template)
        {
            return (new MailMessage)
                    ->view(
                        'admin.emails.invoice_notify',
                        [
                            'email' => $notifiable->email,
                            'name' => $notifiable->name,
                            'system_name' => 'PRE Maintenance',
                            'template' => $template,
                            'link' => $this->notification_detail['route'],
                        ]
                    )->subject($template->subject);
        }
    }

    public function toDatabase($notifiable)
    {
        return [
            'notification_detail' => $this->notification_detail,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
