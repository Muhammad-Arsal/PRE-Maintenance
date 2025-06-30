<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\EmailTemplate;

class InspectionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var array */
    protected $detail;

    /** @var string */
    protected $systemName = 'PRE Maintenance';

    /**
     * Create a new notification instance.
     *
     * @param  array  $detail
     *         [
     *           'property' => '123 Main St, City, County, Postcode',
     *           'date'     => '23/06/2025',
     *           'time'     => '2 hours'
     *         ]
     */
    public function __construct(array $detail)
    {
        $this->detail = $detail;
    }

    /**
     * Channels the notification will be sent through.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation.
     */
    public function toMail($notifiable)
    {
        // Fetch your HTML template record
        $template = EmailTemplate::where('status', '1')
                                 ->where('type', 'Inspection Notification')
                                 ->first();

        if (! $template) {
            // Fallback plain text
            return (new MailMessage)
                ->subject('Inspection Scheduled')
                ->line("Hi {$notifiable->name},")
                ->line('A new inspection has been scheduled.')
                ->line("Property: {$this->detail['property']}")
                ->line("Date:     {$this->detail['date']}")
                ->line("Time:     {$this->detail['time']}")
                ->line('Please log in to view more details.');
        }

        // Decode and replace placeholders
        $html = base64_decode($template->content);

        $html = str_replace(
            ['USER_NAME', 'APP_NAME', 'PROPERTY_ADDRESS', 'INSPECTION_DATE', 'INSPECTION_TIME'],
            [
              $notifiable->name,
              $this->systemName,
              $this->detail['property'],
              $this->detail['date'],
              $this->detail['time'],
            ],
            $html
        );

        return (new MailMessage)
            ->subject($template->subject)
            ->view('admin.emails.inspection_notification', [
                'subject'  => $template->subject,
                'bodyHtml' => $html,
            ]);

    }

    /**
     * Build the array stored in the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'notification_detail' => [
                'type'     => 'Inspection',
                'message'  => 'New Inspection Scheduled',
                'property' => $this->detail['property'],
                'date'     => $this->detail['date'],
                'time'     => $this->detail['time'],
                'route'    => $this->detail['route'],
            ],
        ];
    }


    /**
     * Optional: fallback for array cast.
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
