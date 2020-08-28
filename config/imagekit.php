<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ImageKit Domain
    |--------------------------------------------------------------------------
    |
    | The default domain to use as part of your URL Endpoint.
    |
    */

    'domain' => env('IMAGEKIT_DOMAIN', 'ik.imagekit.io'),

    /*
    |--------------------------------------------------------------------------
    | ImageKit ID
    |--------------------------------------------------------------------------
    |
    | The default ImageKit ID to use as part of your URL Endpoint.
    |
    */

    'id' => env('IMAGEKIT_ID'),

    /*
    |--------------------------------------------------------------------------
    | ImageKit Identifier
    |--------------------------------------------------------------------------
    |
    | The default identifier to use as part of your URL Endpoint.
    |
    */

    'identifier' => env('IMAGEKIT_IDENTIFIER'),

    /*
    |--------------------------------------------------------------------------
    | Bypass ImageKit
    |--------------------------------------------------------------------------
    |
    | You can bypass ImageKit to load the images from the regular path.
    | This is useful for local development.
    |
    */

    'bypass' => env('IMAGEKIT_BYPASS', false),

];
