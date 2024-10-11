<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Tinify\Tinify;
use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Process and optimize user photo using Tinify API.
     *
     * @param UploadedFile|null $photo
     * @return string
     */
    public function processAndOptimizePhoto(?UploadedFile $photo): string
    {
        try {
            // Ensure Tinify API key is set
            \Tinify\setKey(env('TINYPNG_API_KEY'));

            // Generate a unique file name for the photo
            $fileName = 'photos/' . uniqid() . '.jpg';

            // Store the uploaded file temporarily
            $filePath = $photo->storeAs('photos', basename($fileName), 'public');

            // Get the full path to the stored file
            $fullFilePath = storage_path('app/public/' . $filePath);

            // Compress the image using Tinify API
            $source = \Tinify\fromFile($fullFilePath);

            // Resize the image to 70x70 using the 'fit' method
            $resized = $source->resize([
                "method" => "fit",
                "width" => 70,
                "height" => 70
            ]);

            // Save the resized and optimized image back to the same path
            $resized->toFile($fullFilePath);

            // Return the relative file path for storing in the database
            return $filePath;
        } catch (\Exception $e) {
            // Handle any errors during the process
            throw new \RuntimeException('Failed to process the photo. Error: ' . $e->getMessage());
        }
    }
}
