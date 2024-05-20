<?php

namespace App\Models;

use App\Notifications\Auth\QueuedResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_user_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Overrideen sendPasswordResetNotification implementation
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\Auth\QueuedResetPassword($token));
    }

    //Overrideen sendEmailVerificationNotification implementation
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\Auth\QueuedVerifyEmail);
    }
}
