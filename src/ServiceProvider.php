<?php

namespace Aerni\Imagekit;

use Aerni\Imagekit\ImagekitTags;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        ImagekitTags::class
    ];

    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/config/imagekit.php' => config_path('imagekit.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(ImagekitTags::class, function () {
            $config = [
                'domain' => config('imagekit.domain'),
                'id' => config('imagekit.id'),
                'identifier' => config('imagekit.identifier'),
            ];

            return new ImagekitTags($config);
        });
    }
}
