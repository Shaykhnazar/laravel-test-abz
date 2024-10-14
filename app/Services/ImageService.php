<?php

namespace App\Services;

use Illuminate\Http\File;
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
            $tinifyApiKey = env('TINYPNG_API_KEY');
            \Tinify\setKey($tinifyApiKey);

            // Automatically generate a unique ID for filename...
            $path = Storage::disk('public')->putFile('photos', $photo);
            $fullFilePath = Storage::disk('public')->path($path);

//            dd($path, $fullFilePath);

            // Compress the image using Tinify API
            $source = \Tinify\fromFile($fullFilePath);

            // Resize the image to required dimensions using the 'cover' method
            $resized = $source->resize([
                "method" => "cover",
                "width" => 70,
                "height" => 70
            ]);

            // Save the resized and optimized image back to the same path
            $resized->toFile($fullFilePath);

            // Return the relative file path for storing in the database
            return $path;
        } catch (\Exception $e) {
            // Handle any errors during the process
            $errorMessage = 'Failed to process the photo. Error: ' . $e->getMessage();
            throw new \RuntimeException($errorMessage);
        }
    }
}
