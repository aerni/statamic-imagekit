## Installation

1. Simply copy the `Imagekit` folder into `site/addons/`.
2. Configure your default settings.
3. Take a well deserved break.

***

## Default Settings
Head to `Configure -> Addons -> ImageKit` in the CP and configure your default settings. 

> Values configured here will be saved in `site/settings/addons/imagekit.yaml`.

```yaml
domain: ik.imagekit.io
id: starwars
identifier: characters 
```

***

## Basic Usage

### Single Image
We have an asset URL saved in the YAML and want to resize the image to 300x200.

```yaml
image: /assets/starwars/mandalorian.jpg
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
Loop over a list of asset URLs to generate ImageKit URLs for each one. Reference the URL of the current asset in the loop using `{{ value }}`.

```yaml
images:
  - /assets/starwars/mandalorian.jpg
  - /assets/starwars/baby-yoda.jpg
```

```template
{{ images }}
  {{ imagekit:value w="300" h="200" }}
{{ /images }}
```

```output
https://ik.imagekit.io/starwars/characters/tr:w-300,h-200/assets/starwars/mandalorian.jpg
https://ik.imagekit.io/starwars/characters/tr:w-300,h-200/assets/starwars/baby-yoda.jpg
```

***

## HTML Attributes
Pass the following parameters to generate the respective HTML attribute.

| Name | Type | Description |
|------|------|-------------|
| `src` | String | The URL of the image. You can also use the shorthand syntax instead, e.g. `{{ imagekit:image }}`. |
| `tag` | Boolean | When set to `true`, this will output an `<img>` tag with the URL in the `src` attribute. |
| `alt` | String | When using the `tag` parameter, this will insert the given text into the `alt` attribute. |
| `title` | String | When using the `tag` parameter, this will insert the given text into the `title` attribute. |
| `class` | String | When using the `tag` parameter, this will insert the given text into the `class` attribute. |

***

## Settings Parameters
You may want to override the default addon settings for a specific image. You can do this with the following parameters. *Note: Using an empty string will remove the default setting.*

| Name | Type | Description |
|------|------|-------------|
| `domain` | String | Override the default `domain`. |
| `id` | String | Override the default `id`. |
| `identifier` | String | Override the default `identifier`. |

***

## ImageKit Parameters
You may pass any transformation parameters straight from the [ImageKit API](https://docs.imagekit.io/features/image-transformations). For example, `{{ imagekit:image w="300" }}` will use the width transformation parameter. There’s only a few parameters that are not supported by this addon.

| Category | Supported Parameters | Unsupported Parameters |
|----------|----------------------|------------------------|
| [Resize, crop and other transformations](https://docs.imagekit.io/features/image-transformations/resize-crop-and-other-transformations) | `w` `h` `ar` `c` `cm` `fo` `q` `f` `bl` `dpr` `n` `pr` `lo` `t` | `di` |
| [Overlay](https://docs.imagekit.io/features/image-transformations/overlay) | | `oi` `ofo` `ox` `oy` `oh` `ow` `ot` `oit` `otc` `otf` `ots` `ott` `oa` `obg` |
| [Image Enhancement & Color Manipulation](https://docs.imagekit.io/features/image-transformations/image-enhancement-and-color-manipulation) | `e-contrast` `e-sharpen` `e-usm` `e-grayscale` |  |
| [Other transformations](https://docs.imagekit.io/features/image-transformations/others-transformations) | `cp` `md` `rt` `r` `bg` | `orig` |

## Chained Transformations
You can take advantage of [Chained Transformations](https://docs.imagekit.io/features/image-transformations/chained-transformations) by adding a `:` at the end of a given parameter. For example, `{{ imagekit:image w="300" h="200:" rt="90"}}` will first resize the image to 300x200 and then apply a rotation of 90 degrees.

***

## Lazyloading with lazySizes
If you’re using lazySizes to lazyload images, chances are you’re using the [lazySizes RIaS extension](https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/rias). You can leverage the power of the `{width}` and `{quality}` placeholder by setting the value of the `w` and/or `q` parameter to `auto`.


```template
{{ imagekit:image w="auto" q="auto" }}
```

```output
https://ik.imagekit.io/starwars/characters/tr:w-{width},q-{auto}/assets/starwars/mandalorian.jpg
```
