<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection', 'disk', 'path', 'visibility'
    ];

    protected $appends = ['full_url'];

    public function mediable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function getFullUrlAttribute(): string
    {
        $disk = Storage::disk($this->disk);

        if ($this->visibility === 'public') {
            return $disk->url($this->path);
        }

        if ($this->disk === 's3') {
            return $disk->temporaryUrl($this->path, now()->addMinutes(15));
        }

        // TODO: signed route
    }
}
