<?php



namespace App\Notifications;



use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;

use Illuminate\Support\Facades\Lang;

use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use Illuminate\Support\Str;

use App\Models\User;

use App\Models\UserEmailVerification;
use App\Models\EmailTemplate;
use App\Models\Setting;

use Illuminate\Support\Facades\Hash;



class UserEmailVerifyNotification extends Notification

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
                        'user.emails.verify-account',
                        [
                            'link' => $verificationUrl,
                            'email' => $notifiable->email,
                            'name' => $notifiable->name,
                            'system_name' => 'Benhams Tech',
                            'template' => $template
                        ]
                    )->subject($template->subject);


        }else{

            $verificationUrl = $this->verificationUrl($notifiable);

            return (new MailMessage)

            ->subject(Lang::get('Verify Email Address'))

            ->line(Lang::get('Please click the button below to verisfy your email address and Enter a new password for your account'))

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

        $user_id = $this->data->id;

        UserEmailVerification::create([

            'user_id' => $user_id,

            'token' => $token,

        ]);



        return URL::temporarySignedRoute(

            'user.email.verify',

            now()->addMinutes(1),

            [

                'id'   => $user_id,

                'token' => $token,

            ]

        );

    }

}

