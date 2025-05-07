<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail

{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'team_id',
        'email',
        'password',
        'explanation',
        'is_active',
        'last_login_at',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $appends = ['avatar_full'];

    public function getAvatarFullAttribute()
    {
        return $this->avatar ? Config::get('app.image_url') . '/' . $this->avatar : null;
    }

    public function getCreatedAtAttribute($value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getEmailVerifiedAtAttribute($value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
