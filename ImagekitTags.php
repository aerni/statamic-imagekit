<?php

namespace Statamic\Addons\Imagekit;

use Statamic\Extend\Tags;
use Statamic\Addons\Imagekit\Validator;

class ImagekitTags extends Tags
{
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
     * Maps to {{ imagekit:field }}
     *
     * Where `field` is the variable containing the image path
     *
     * @param $method
     * @param $args
     * @return string
     */
    public function __call($method, $args)
    {
        $tag = explode(':', $this->tag, 2)[1];

        $item = array_get($this->context, $tag);

        return $this->output($this->buildUrl($item));
    }

    /**
     * Maps to {{ imagekit }}
     *
     * Alternate syntax, where you pass the path as a parameter
     *
     * @return string
     */
    public function index()
    {
        $item = $this->getParam(['src']);

        return $this->output($this->buildUrl($item));
    }

    /**
     * Output the tag
     *
     * @param string $url
     * @return string
     */
    private function output($url)
    {
        $src = "src=\"{$url}\"";
        $class = $this->getParam('class') ? "class=\"{$this->getParam('class')}\"" : '';
        $alt = $this->getParam('alt') ? "alt=\"{$this->getParam('alt')}\"" : "alt=\"\"";
        $title = $this->getParam('title') ? "title=\"{$this->getParam('title')}\"" : '';

        if ($this->getParam('tag')) {
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
    private function buildUrl($item)
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
    private function buildImagekitEndpoint()
    {
        $endpointConfig = $this->getAddonConfig();

        $endpoint = 'https://' . implode('/', array_filter($endpointConfig));

        return $endpoint;
    }

    /**
     * Get the addon config
     *
     * @return string
     */
    private function getAddonConfig()
    {
        $config = [
            'domain' => $this->get('domain', ''),
            'id' => $this->get('id', ''),
            'identifier' => $this->get('identifier', '')
        ];

        Validator::validateConfig($config);

        return $config;
    }

    /**
     * Get the ImageKit parameters from the tag
     *
     * @return string
     */
    private function getImagekitParams()
    {
        $imagekitParams = [];

        foreach ($this->parameters as $param => $value) {
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
     * @return string
     */
    private function normalizeImagekitParams($imagekitParams)
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
     * Build a ImageKit transformation string
     *
     * @return string
     */
    private function buildImagekitTransformation()
    {
        $params = $this->getImagekitParams();
        $paramPairs = [];

        if (isset($params)) {
            foreach ($params as $param => $value) {
                if ($value === '') {
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
