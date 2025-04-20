<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Otp extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at'];

    protected $dates = ['expires_at'];
}
