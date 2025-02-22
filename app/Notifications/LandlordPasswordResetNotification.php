<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;

use Illuminate\Support\Facades\Lang;

use App\Models\EmailTemplate;



class LandlordPasswordResetNotification extends Notification

{

    use Queueable;

    protected $token;



    /**

     * Create a new notification instance.

     *

     * @return void

     */

    public function __construct($token)

    {

        $this->token =  $token;

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

        $originalToken = $this->getToken($notifiable);
        $template =  EmailTemplate::where('status','1')->where('type','reset password')->first();

        if($template){

            return (new MailMessage)
                    ->view(
                        'landlord.emails.reset-password',
                        [
                            'token' => $originalToken,
                            'link' => config('app.url').route('landlord.showResetForm', ['token' => $this->token], false)."?email=".$originalToken,
                            'email' => $notifiable->email,
                            'name' => $notifiable->name,
                            'template' => $template
                        ]
                    )->subject($template->subject);
          }else{


            return (new MailMessage)

                        ->subject(Lang::get('Reset Password Notifications'))

                        ->line(Lang::get('You are getting this email because we received a password reset request for you account.'))

                        ->action(Lang::get('Reset Password'),

                        url(config('app.url').route('landlord.showResetForm', ['token' => $this->token], false)."?email=".$originalToken))

                        ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.users.expire')]))

                        ->line(Lang::get('If you did not request a password reset, no further action is required.'));
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


    public function getToken($notifiable) {

        $email = $notifiable->getEmailForVerification();

        return $email;

    }

}

