<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');
        
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \Exception('Cloudinary credentials are not configured. Please set CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET in your .env file.');
        }
        
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array{url: string, public_id: string}
     * @throws \Exception
     */
    public function uploadImage(UploadedFile $file, string $folder = 'uploads'): array
    {
        // Validate file
        $this->validateFile($file);

        // Upload to Cloudinary
        $uploadApi = $this->cloudinary->uploadApi();
        
        $result = $uploadApi->upload($file->getRealPath(), [
            'folder' => 'RestaurantApp/' . $folder,
            'resource_type' => 'image',
            'overwrite' => false,
            'invalidate' => true,
            'quality' => 'auto:good',
            'fetch_format' => 'auto',
        ]);
        
        Log::info('Image uploaded to Cloudinary', [
            'folder' => $folder,
            'public_id' => $result['public_id'],
            'url' => $result['secure_url'],
        ]);
        
        return [
            'url' => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        if (empty($publicId)) {
            return true;
        }

        try {
            $uploadApi = $this->cloudinary->uploadApi();
            
            $result = $uploadApi->destroy($publicId, [
                'resource_type' => 'image',
                'invalidate' => true,
            ]);
            
            Log::info('Image deleted from Cloudinary', [
                'public_id' => $publicId,
                'result' => $result['result'],
            ]);
            
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed', [
                'error' => $e->getMessage(),
                'public_id' => $publicId,
            ]);
            return false;
        }
    }

    /**
     * Validate uploaded file
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    protected function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Invalid file type. Only images are allowed.');
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds 10MB limit');
        }
    }

    /**
     * Get Cloudinary instance
     *
     * @return Cloudinary
     */
    public function getCloudinary(): Cloudinary
    {
        return $this->cloudinary;
    }
}
