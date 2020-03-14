<?php

namespace Aerni\Imagekit;

use Aerni\Imagekit\ImagekitTags;
use Statamic\Providers\AddonServiceProvider;

class ImagekitServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        ImagekitTags::class
    ];

    protected $publishables = [
        __DIR__ . '/config/imagekit.php' => 'config/statamic/imagekit.php',
    ];

    public function register()
    {
        $this->app->singleton(ImagekitTags::class, function () {
            $config = [
                'domain' => config('statamic.imagekit.domain'),
                'id' => config('statamic.imagekit.id'),
                'identifier' => config('statamic.imagekit.identifier'),
            ];

            return new ImagekitTags($config);
        });
    }
}
