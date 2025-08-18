<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminPasswordResetNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
// use App\Notifications\AdminVerifyEmailNotification;


class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;
    // use SoftDeletes;

    protected $table = 'admins';
    protected $guard = 'admin';
    protected $guard_name = 'admin';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile(){
        return $this->hasOne(AdminProfile::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminPasswordResetNotification($token));
    }

    // public function SendEmailVerificationNotification(){
    //     $this->notify(new AdminVerifyEmailNotification());
    // }
}
