<?php

namespace Aerni\Imagekit;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        ImagekitTags::class
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(ImagekitTags::class, function () {
            return new ImagekitTags([
                'domain' => config('imagekit.domain'),
                'id' => config('imagekit.id'),
                'identifier' => config('imagekit.identifier'),
                'bypass' => config('imagekit.bypass'),
            ]);
        });
    }
}
