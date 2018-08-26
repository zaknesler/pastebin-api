<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paste extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'body',
        'language',
        'visibility',
        'expires_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Determine if a paste is marked as private.
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->visibility == 'private';
    }

    /**
     * Determine if a paste is owned by a user.
     *
     * @param  \App\User|null  $user
     * @return boolean
     */

    public function isOwnedBy(User $user = null)
    {
        if (is_null($user)) {
            return false;
        }

        return (bool) optional($this->user_id) == $user->id;
    }

    /**
     * A paste can belong to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
