<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class StorageService
{
    /**
     * Store an image in the public / images
     * @param mixed $imgFile
     * @param mixed $directory
     * @param mixed $prefix
     */
    public static function storeImage($imgFile, $directory, $prefix = null)
    {
        // Sanitize file name
        $imgName = $prefix . uniqid() . '.' . $imgFile->getClientOriginalExtension();
        // Store the file
        $imgFile->storeAs('public/images/' . $directory, $imgName);

        return  $directory . '/' . $imgName;
    }

    /**
     * Create a thumbnail for an image
     * @param mixed $imgFile
     * @param mixed $directory
     * @param mixed $prefix
     */
    public static function createThumbnail($imgFile, $directory, $prefix = null)
    {
        $imgName = $prefix . uniqid() . '.' . $imgFile->getClientOriginalExtension();
        // read & resize & save the image
        $thumbnail = Image::read($imgFile->getRealPath());
        $thumbnail->resize(150, 150);
        $thumbnail->save(public_path('storage/images/') . $directory . '/' . $imgName);
        return $directory . '/' . $imgName;
    }
}
