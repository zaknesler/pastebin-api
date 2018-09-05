<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Paste $paste) {
            $paste->slug = str_random(8);
        });
    }

    /**
     * Scope only the publicly visible pastes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic(Builder $query)
    {
        return $query->where('visibility', 'public');
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
     * Determine if a paste has expired.
     *
     * @return boolean
     */
    public function hasExpired()
    {
        return optional($this->expires_at)->lte(now());
    }

    /**
     * Determine if a paste is owned by a user.
     *
     * @param  \App\User|null  $user
     * @return boolean
     */

    public function isOwnedBy(User $user = null)
    {
        if (is_null($user) || is_null($this->user)) {
            return false;
        }

        return $this->user_id == $user->id;
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
