<?php

namespace Aerni\Imagekit;

use Statamic\Tags\Tags;
use Aerni\Imagekit\Validator;

class Imagekit extends Tags
{
    /**
     * Set the tag handle to a custom name
     *
     * @var string
     */
    protected static $handle = 'imagekit';

    /**
     * The config array
     *
     * @var array
     */
    private $config = [];

    /**
     * Supported ImageKit API parameters
     *
     * @var array
     */
    private $imagekitApi = [
        'w', 'h', 'ar', 'c', 'cm', 'fo', 'q', 'f', 'bl', 'e-grayscale', 'dpr', 'n', 'pr', 'lo', 't', 
        'b', 'cp', 'md', 'rt', 'r', 'bg', 'orig', 'e-contrast', 'e-sharpen', 'e-usm'
    ];

    /**
     * Tag config parameters
     *
     * @var array
     */
    private $tagAttrs = [
        'src', 'class', 'alt', 'title', 'tag', 'domain', 'id', 'identifier'
    ];

    /**
     * Construct the class with a config array.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        Validator::validateConfig($this->config);
    }

    /**
     * Maps to {{ imagekit:field }}
     *
     * Where `field` is the variable containing the image path
     *
     * @param string $tag
     * @return string
     */
    public function wildcard(string $tag): string
    {
        $item = $this->context->get($tag);

        return $this->output($this->buildUrl($item));
    }

    /**
     * Maps to {{ imagekit }}
     *
     * Alternate syntax, where you pass the path using the `src` parameter
     *
     * @return string
     */
    public function index(): string
    {
        $item = $this->params->get('src');

        return $this->output($this->buildUrl($item));
    }

    /**
     * Output the tag
     *
     * @param string $url
     * @return string
     */
    private function output(string $url): string
    {
        $src = "src=\"{$url}\"";
        $class = $this->params->get('class') ? "class=\"{$this->params->get('class')}\"" : '';
        $alt = $this->params->get('alt') ? "alt=\"{$this->params->get('alt')}\"" : "alt=\"\"";
        $title = $this->params->get('title') ? "title=\"{$this->params->get('title')}\"" : '';

        if ($this->params->get('tag')) {
            return "<img {$src} {$class} {$alt} {$title} />";
        }

        return $url;
    }

    /**
     * Build the final ImageKit URL
     *
     * @param string $item. The path of the image.
     * @return string
     */
    private function buildUrl(string $item): string
    {
        $urlParts = [
            'endpoint' => $this->buildImagekitEndpoint(),
            'transformation' => $this->buildImagekitTransformation(),
            'path' => trim($item, '/')
        ];

        $url = implode('/', array_filter($urlParts));

        return $url;
    }

    /**
     * Build the ImageKit endpoint URL
     *
     * @return string
     */
    private function buildImagekitEndpoint(): string
    {
        $endpointConfig = $this->getConfig();

        $endpoint = 'https://' . implode('/', array_filter($endpointConfig));

        return $endpoint;
    }

    /**
     * Get the config from the tag
     *
     * @return array
     */
    private function getConfig(): array
    {
        $config = [
            'domain' => $this->params->get('domain'),
            'id' => $this->params->get('id'),
            'identifier' => $this->params->get('identifier')
        ];

        $config = $this->mergeConfig($config);

        Validator::validateConfig($config);

        return $config;
    }

    /**
     * Merge the addon config with the config provided on the tag
     *
     * @param array $config. An array of the addon's config.
     * @return array
     */
    private function mergeConfig(array $config): array
    {
        $defaultConfig = collect(array_filter($this->config));
        $tagConfig = collect($config);

        $validTagConfig = $tagConfig->filter(function ($value) {
            return $value !== null;
        });

        $mergedConfig = $defaultConfig->merge($validTagConfig)->toArray();

        return $mergedConfig;
    }

    /**
     * Get the ImageKit parameters from the tag
     *
     * @return array
     */
    private function getImagekitParams(): array
    {
        $imagekitParams = [];

        foreach ($this->params as $param => $value) {
            if (!in_array($param, $this->tagAttrs)) {
                $imagekitParams[$param] = $value;
            }
        }

        $normalizedParams = $this->normalizeImagekitParams($imagekitParams);

        Validator::validateImagekitParams($normalizedParams, $this->imagekitApi);

        return $normalizedParams;
    }

    /**
     * Normalize the values of the ImageKit parameters
     *
     * @param array $imagekitParams. An array of ImageKit parameters.
     * @return array
     */
    private function normalizeImagekitParams(array $imagekitParams): array
    {

        if (!empty($imagekitParams)) {

            foreach ($imagekitParams as $param => $value) {

                // Remove empty spaces from parameter values
                if (is_string($value)) {
                    $value = trim($value);
                    $imagekitParams[$param] = $value;
                }

                // Convert booleans to strings
                if (is_bool($value)) {
                    $imagekitParams[$param] = var_export($value, true);
                }

                // For use with lazysizes rias plugin
                if ($param === 'w' && $value === 'auto') {
                    $imagekitParams['w'] = '{width}';
                }

                // For use with lazysizes rias plugin
                if ($param === 'q' && $value === 'auto') {
                    $imagekitParams['q'] = '{quality}';
                }
            }
        }

        return $imagekitParams;
    }

    /**
     * Build an ImageKit transformation string
     *
     * @return string
     */
    private function buildImagekitTransformation(): string
    {
        $params = $this->getImagekitParams();
        $paramPairs = [];

        if (isset($params)) {
            foreach ($params as $param => $value) {
                if (empty($value)) {
                    $paramPairs[] = $param;
                } else {
                    $paramPairs[] = $param . "-" . $value;
                }
            }
        }

        $joinedParams = join(',', $paramPairs);

        $transformation = empty($joinedParams) ? '' : 'tr:' . $joinedParams;

        return $transformation;
    }
}
