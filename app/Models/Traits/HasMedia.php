<?php
namespace App\Models\Traits;

use App\Models\MediaCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMedia
{
    public static function bootHasMedia(): void
    {
        static::deleting(function ($model) {
            $model->media()->each(function (MediaCollection $media) {
                Storage::disk($media->disk)->delete($media->path);
                $media->delete();
            });
        });
    }

    public function media(): MorphMany
    {
        return $this->morphMany(MediaCollection::class, 'mediable');
    }

    public function addMedia(
        UploadedFile $file,
        string $disk = 's3',
        string $visibility = 'private',
        string $collection = null,
    ): \Illuminate\Database\Eloquent\Model
    {
        $collection = $collection ?? $this->getDefaultMediaCollection();

        $path = $file->store("media/{$this->getTable()}/{$this->id}", [
            'disk' => $disk,
            'visibility' => $visibility,
        ]);

        return $this->media()->create([
            'disk' => $disk,
            'path' => $path,
            'visibility' => $visibility,
            'collection' => $collection,
        ]);
    }

    public function updateMedia(
        UploadedFile $file,
        string $disk = 's3',
        string $visibility = 'private',
        string $collection = null,
    ): \Illuminate\Database\Eloquent\Model
    {
        $collection = $collection ?? $this->getDefaultMediaCollection();

        $this->media()
            ->when($collection, fn($q) => $q->where('collection', $collection))
            ->each(function (MediaCollection $media) {
                Storage::disk($media->disk)->delete($media->path);
                $media->delete();
            });

        return $this->addMedia($file, $disk, $visibility, $collection);
    }

    public function getFirstMediaUrl(string $collection = null): ?string
    {
        $query = $this->media();
        $collection = $collection ?? $this->getDefaultMediaCollection();

        if ($collection) {
            $query->where('collection', $collection);
        }

        $media = $query->first();

        return $media?->full_url;
    }

    public function getAllMediaUrls(string $collection = null): array
    {
        $query = $this->media();
        $collection = $collection ?? $this->getDefaultMediaCollection();

        if ($collection) {
            $query->where('collection', $collection);
        }

        return $query->get()->map->full_url->toArray();
    }

    protected function getDefaultMediaCollection(): ?string
    {
        return property_exists($this, 'defaultMediaCollection')
            ? $this->defaultMediaCollection
            : null;
    }
}
