<?php

namespace Aerni\Imagekit;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Imagekit::class
    ];

    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/../config/imagekit.php' => config_path('imagekit.php'),
        ]);
    }

    public function register()
    {
        parent::register();

        $this->app->bind(Imagekit::class, function () {
            $config = [
                'domain' => config('imagekit.domain'),
                'id' => config('imagekit.id'),
                'identifier' => config('imagekit.identifier'),
            ];

            return new Imagekit($config);
        });
    }
}
