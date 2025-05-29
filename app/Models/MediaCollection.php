<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class MediaCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection', 'disk', 'path', 'visibility'
    ];

    protected $appends = ['full_url'];

    const DEFAULT_EXPIRE_TIME = 60;

    public function mediable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function getFullUrlAttribute(): ?string
    {
        $disk = $this->disk;
        $path = $this->path;
        $visibility = $this->visibility;

        if ($disk === 'minio') {
            config(['filesystems.disks.minio.endpoint' => config('filesystems.disks.minio.access_endpoint')]);
        }

        if ($visibility === 'private') {
            if ($disk === 's3' || $disk === 'minio') {
                return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(self::DEFAULT_EXPIRE_TIME));
            } else {
                return URL::signedRoute('media.private', ['media' => $this->id], now()->addMinutes(self::DEFAULT_EXPIRE_TIME));
            }
        } else {
            if ($disk === 'local') {
                return URL::signedRoute('media.private', ['media' => $this->id], now()->addMinutes(self::DEFAULT_EXPIRE_TIME));
            }
        }

        return Storage::disk($disk)->url($path);
    }
}
