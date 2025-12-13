<?php

namespace App\Providers;

use App\Services\CloudinaryService;
use Cloudinary\Cloudinary;
use Illuminate\Support\ServiceProvider;

class CloudinaryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Cloudinary::class, function () {
            $config = config('services.cloudinary');
            
            if (empty($config['cloud_name']) || empty($config['api_key']) || empty($config['api_secret'])) {
                throw new \RuntimeException(
                    'Cloudinary credentials are not configured. Please set CLOUDINARY_CLOUD_NAME, ' .
                    'CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET in your .env file.'
                );
            }
            
            return new Cloudinary([
                'cloud' => [
                    'cloud_name' => $config['cloud_name'],
                    'api_key' => $config['api_key'],
                    'api_secret' => $config['api_secret'],
                ],
                'url' => ['secure' => true],
            ]);
        });

        $this->app->singleton(CloudinaryService::class);
    }
}
