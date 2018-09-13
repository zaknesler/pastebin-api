<?php

namespace App;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the URL of the user's avatar.
     *
     * @param  integer  $size
     * @return string
     */
    public function getAvatar($size = 50)
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->email) . '?s=' . $size . '&d=mp';
    }

    /**
     * A user can have many pastes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastes()
    {
        return $this->hasMany(Paste::class);
    }
}
