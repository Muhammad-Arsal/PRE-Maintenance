<?php



namespace App\Notifications;



use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;

use Illuminate\Support\Facades\Lang;

use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\URL;


use Illuminate\Support\Str;


use App\Models\LandlordEmailVerification;
use App\Models\EmailTemplate;

use Illuminate\Support\Facades\Hash;



class LandlordEmailVerifyNotification extends Notification

{

    use Queueable;



    protected $data;

    /**

     * Create a new notification instance.

     *

     * @return void

     */

    public function __construct($data)

    {

        $this->data = $data;

    }



    /**

     * Get the notification's delivery channels.

     *

     * @param  mixed  $notifiable

     * @return array

     */

    public function via($notifiable)

    {

        return ['mail'];

    }



    /**

     * Get the mail representation of the notification.

     *

     * @param  mixed  $notifiable

     * @return \Illuminate\Notifications\Messages\MailMessage

     */

    public function toMail($notifiable)

    {

        $template =  EmailTemplate::where('status','1')->where('type','User Account Verification')->first();
        $verificationUrl = $this->verificationUrl($notifiable);

        if($template){

            return (new MailMessage)
                    ->view(
                        'landlord.emails.verify-account',
                        [
                            'link' => $verificationUrl,
                            'email' => $notifiable->email,
                            'name' => $notifiable->name,
                            'system_name' => 'PRE Maintenance',
                            'template' => $template
                        ]
                    )->subject($template->subject);


        }else{

            $verificationUrl = $this->verificationUrl($notifiable);

            return (new MailMessage)

            ->subject(Lang::get('Verify Email Address'))

            ->line(Lang::get('Please click the button below to verify your email address and Enter a new password for your account'))

            ->action(Lang::get('Verify Email Address'), $verificationUrl)

            ->line(Lang::get('If you did not create an account, no further action is required.'));
        }

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



    protected function verificationUrl($notifiable)
    {

        $token = Str::random(60);

        $landlord_id = $this->data->id;

        LandlordEmailVerification::create([

            'landlord_id' => $landlord_id,

            'token' => $token,
        ]);



        return URL::temporarySignedRoute(

            'landlord.email.verify',

            now()->addMinutes(60),

            [
                'id'   => $landlord_id,

                'token' => $token,
            ]

        );
    }
}

