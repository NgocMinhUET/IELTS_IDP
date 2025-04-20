<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model

{
    use SoftDeletes;

    /**
     * getCreatedAtAttribute 
     *
     * @param [type] $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * getUpdatedAtAttribute
     *
     * @param [type] $value
     * @return string
     */
    public function getUpdatedAtAttribute($value): string
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}
