<?php

namespace App\Http\Controllers\CMS;

use App\Models\MediaCollection;
use Illuminate\Support\Facades\Storage;

class MediaController extends CMSController
{
    public function streamPrivate(MediaCollection $media): \Symfony\Component\HttpFoundation\StreamedResponse
    {
//        abort_unless($media->visibility === 'private', 403);

        return Storage::disk($media->disk)->response($media->path);
    }
}