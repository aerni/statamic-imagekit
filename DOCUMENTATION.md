## Installation
Install the addon using Composer.

```bash
composer require aerni/imagekit
```

Publish the config of the package.

```bash
php please vendor:publish --tag=imagekit-config
```

The following config will be published to `config/imagekit.php`.

```php
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
```

## Configuration
Set your configuration in your `.env` file. The values will be used to generate your URL Endpoint.

```env
IMAGEKIT_DOMAIN=ik.imagekit.io
IMAGEKIT_ID=starwars
IMAGEKIT_IDENTIFIER=characters
IMAGEKIT_BYPASS=true
```

## Basic Usage

### Single Image
We have an asset URL saved in the YAML and want to resize the image to 300x200.

```yaml
image: starwars/mandalorian.jpg
```

```template
{{ imagekit :src="image" w="300" h="200" }}

<!-- Use the nicer shorthand syntax: -->
{{ imagekit:image w="300" h="200" }}
```

```output
https://ik.imagekit.io/starwars/characters/tr:w-300,h-200/assets/starwars/mandalorian.jpg
```

### Multiple Images
Loop over a list of asset URLs to generate ImageKit URLs for each one. Reference the URL of the current asset in the loop using `{{ url }}`.

```yaml
images:
  - starwars/mandalorian.jpg
  - starwars/baby-yoda.jpg
```

```template
{{ images }}
  {{ imagekit:url w="300" h="200" }}
{{ /images }}
```

```output
https://ik.imagekit.io/starwars/characters/tr:w-300,h-200/assets/starwars/mandalorian.jpg
https://ik.imagekit.io/starwars/characters/tr:w-300,h-200/assets/starwars/baby-yoda.jpg
```

## Focal Point
Use Statamic's Focal Point feature to automatically add the `xc` and `yc` parameter to the image.

**Image Meta Yaml:**
```yaml
data:
  focus: 50-85-1
width: 2090
height: 3000

```

**Template:**
```template
{{ imagekit:image w="500" h="200" cm="extract" }}
```

**Output:**
```output
https://ik.imagekit.io/starwars/characters/tr:xc-1045,yc-2550,w-500,h-200,cm-extract/assets/starwars/mandalorian.jpg
```

>**Note:** As of now, ImageKit only supports custom coordinates in combination with the `cm-extract` parameter.

## HTML Attributes
Pass the following parameters to generate the respective HTML attribute.

| Name | Type | Description |
|------|------|-------------|
| `src` | String | The URL of the image. You can also use the shorthand syntax instead, e.g. `{{ imagekit:image }}`. |
| `tag` | Boolean | When set to `true`, this will output an `<img>` tag with the URL in the `src` attribute. |
| `alt` | String | When using the `tag` parameter, this will insert the given text into the `alt` attribute. |
| `title` | String | When using the `tag` parameter, this will insert the given text into the `title` attribute. |
| `class` | String | When using the `tag` parameter, this will insert the given text into the `class` attribute. |

## Settings Parameters
You may want to override the default addon configuration for a specific image. You can do this with the following parameters. *Note: Using an empty string will remove the default setting.*

| Name | Type | Description |
|------|------|-------------|
| `domain` | String | Override the default `domain`. |
| `id` | String | Override the default `id`. |
| `identifier` | String | Override the default `identifier`. |
| `bypass` | Boolean | Override the default `bypass` boolean. |

## ImageKit Parameters
You may pass any transformation parameters straight from the [ImageKit API](https://docs.imagekit.io/features/image-transformations). For example, `{{ imagekit:image w="300" }}` will use the width transformation parameter. There’s only a few parameters that are not supported by this addon.

| Category | Supported Parameters | Unsupported Parameters |
|----------|----------------------|------------------------|
| [Resize, crop and other common transformations](https://docs.imagekit.io/features/image-transformations/resize-crop-and-other-transformations) | `w` `h` `ar` `c` `cm` `fo` `x` `y` `xc` `cy` `q` `f` `bl` `e-grayscale` `dpr` `n` `pr` `lo` `t` `b` `cp` `md` `rt` `r` `bg` `orig` | `di` |
| [Overlay](https://docs.imagekit.io/features/image-transformations/overlay) | | `oi` `obg` `ofo` `ox` `oy` `oh` `ow` `oit` `ot` `ote` `otw` `otbg` `otp` `otia` `otc` `otf` `ots` `ott` `oa` |
| [Image enhancement & color manipulation](https://docs.imagekit.io/features/image-transformations/image-enhancement-and-color-manipulation) | `e-contrast` `e-sharpen` `e-usm` | |

## Chained Transformations
You can take advantage of [Chained Transformations](https://docs.imagekit.io/features/image-transformations/chained-transformations) by adding a `:` at the end of a given parameter. For example, `{{ imagekit:image w="300" h="200:" rt="90" }}` will first resize the image to 300x200 and then apply a rotation of 90 degrees.

## Lazyloading with lazySizes
If you’re using lazySizes to lazyload images, chances are you’re using the [lazySizes RIaS extension](https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/rias). You can leverage the power of the `{width}` and `{quality}` placeholder by setting the value of the `w` and/or `q` parameter to `auto`.


```template
{{ imagekit:image w="auto" q="auto" }}
```

```output
https://ik.imagekit.io/starwars/characters/tr:w-{width},q-{auto}/assets/starwars/mandalorian.jpg
```
