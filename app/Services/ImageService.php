<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Image;

class ImageService {
    protected string $disk = 'public';
    protected string $basePath = 'images/';

    /**
     * Upload and store an image.
     */
    public function uploadImage(
        UploadedFile $file,
        string $type,
        $model,
        int $width = null,
        int $height = null
    ): Image {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $folder = "{$this->basePath}{$type}/";

        // Ensure directory exists
        Storage::disk($this->disk)->makeDirectory($folder);

        // Intervention image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

        if ($width && $height)
        {
            $image->cover($width, $height);
        }

        // Save using storage disk
        $path = $folder . $filename;
        $fullPath = Storage::disk($this->disk)->path($path);
        $image->save($fullPath);

        // Store record
        $imageRecord = Image::create([
            'filename' => $filename,
            'type' => $type,
            'imageable_id' => $model->id,
            'imageable_type' => get_class($model),
            'width' => $width ?? $image->width(),
            'height' => $height ?? $image->height(),
            'size' => Storage::disk($this->disk)->size($path),
            'mime_type' => $file->getMimeType(),
        ]);

        return $imageRecord;
    }

    public function upload(
        UploadedFile $file,
        string $type,
        int $width = null,
        int $height = null
    ): string {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $folder = "{$this->basePath}{$type}/";

        // Ensure directory exists
        Storage::disk($this->disk)->makeDirectory($folder);

        // Intervention image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

        if ($width && $height) {
            $image->cover($width, $height);
        }

        // Save using storage disk
        $path = $folder . $filename;
        $fullPath = Storage::disk($this->disk)->path($path);
        $image->save($fullPath);
        
        return $path; // Return the storage path
    }
    /**
     * Delete an existing image.
     */
    public function deleteImage(Image $image): bool
    {
        $path = "{$this->basePath}{$image->type}/{$image->filename}";

        if (Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }

        return $image->delete();
    }

    /**
     * Get Image URL
     */
    public function getImageUrl(Image $image): string
    {
        return Storage::disk($this->disk)->url("{$this->basePath}{$image->type}/{$image->filename}");
    }

    /**
     * Get Image Path
     */
    public function getImagePath(Image $image): string
    {
        return "{$this->basePath}{$image->type}/{$image->filename}";
    }

    /**
     * Validate file type
     */
    public function validateFile(UploadedFile $file): bool
    {
        $validMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        
        return in_array($file->getMimeType(), $validMimes);
    }
}