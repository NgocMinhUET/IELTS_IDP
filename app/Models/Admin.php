<?php

namespace App\Models;

use App\Enum\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'status',
        'role',
        'created_by',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeIsNotActive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role == UserRole::TEACHER;
    }
}
