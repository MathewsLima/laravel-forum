<?php

namespace App;

use App\Traits\WithPolicy;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, WithPolicy;

    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmation_token'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($value)
    {
        return empty($value) ? '/images/default.jpg' : url("/storage/{$value}");
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at'  => $this->freshTimestamp(),
            'confirmation_token' => null
        ])->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['JohnDoe'], true);
    }

    public function read(Thread $thread)
    {
        cache()->forever(
            auth()->user()->visitedThreadCacheKey($thread),
            now()
        );
    }

    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf('user.%s.visits.%s', $this->id, $thread->id);
    }
}
