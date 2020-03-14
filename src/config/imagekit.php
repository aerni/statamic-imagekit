<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ImageKit Domain
    |--------------------------------------------------------------------------
    |
    | The domain that will be used as the root of your image manipulations.
    | If you don’t opt for a custom domain name, this defaults to ik.imagekit.io.
    |
    */

    'domain' => env('IMAGEKIT_DOMAIN', 'ik.imagekit.io'),

    /*
    |--------------------------------------------------------------------------
    | ImageKit ID
    |--------------------------------------------------------------------------
    |
    | Your ImageKit ID is a unique identifier for your account. 
    | If you don’t opt for a custom domain name, your ImageKit ID will become part of your image URLs.
    |
    */

    'id' => env('IMAGEKIT_ID'),

    /*
    |--------------------------------------------------------------------------
    | ImageKit Endpoint Identifier
    |--------------------------------------------------------------------------
    |
    | The identifier of your endpoint. This will become part of your image URLs.
    |
    */

    'identifier' => env('IMAGEKIT_IDENTIFIER'),

];
