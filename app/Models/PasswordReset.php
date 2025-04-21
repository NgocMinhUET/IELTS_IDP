<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PasswordReset extends Model
{
    use Notifiable;

    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email', 'token'
    ];
}
