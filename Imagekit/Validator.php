<?php

namespace Statamic\Addons\Imagekit;

use Exception;
use Statamic\API\Str;

class Validator
{
    /**
     * Validate the addon's config
     *
     * @param array $config. An array of the addon's config.
     */
    public static function validateConfig($config)
    {
        extract($config);

        foreach ($config as $param => $value) {
            if (! is_string($value)) {
                throw new Exception("The value of [{$param}] must be a string. Please check your config.");
            }
        }
        
        if ($domain === '') {
            throw new Exception("The value of [domain] can not be empty. Please provide a valid domain in the config or on the tag, e.g. \"ik.imagekit.io\".");
        }

        if (Str::startsWith($domain, ['http://', 'https://'])) {
            throw new Exception("The value of [domain] should not include a protocol, e.g. \"ik.imagekit.io\"."); 
        }
    }

    /**
     * Validate the parameters passed in the tag
     *
     * @param array $params. An array of the parameters passed in the tag
     * @param array $api. An array of the API with the supported parameters
     */
    public static function validateImagekitParams($params, $api)
    {
        foreach ($params as $param => $value) {
            if (!in_array($param, $api)) {
                throw new Exception("You are trying to use [{$param}] as a transformation parameter. This parameter does not exist or is not supported by this addon.");
            }
        }
    }
}