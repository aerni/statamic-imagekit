# ImageKit ![Statamic](https://img.shields.io/badge/statamic-2.10-blue.svg?style=flat-square)
> ImageKit provides an easy way to generate ImageKit URLs using a new `{{ imagekit }}` tag.

## Getting Started
To get started and for a list of available options, read the docs on the [Statamic Marketplace](https://statamic.com/marketplace/addons/imagekit/v1/docs) and get familiar with the [ImageKit API](https://docs.imagekit.io/features/image-transformations)

## Basic Usage
We have an asset URL saved in the YAML and want to resize the image to 300x200.

**YAML**
```yaml
image: /assets/starwars/mandalorian.jpg
```

**Template**
```template
{{ imagekit:image w="300" h="200" }}
```

**Output**
```output
https://ik.imagekit.io/starwars/characters/tr:w-300,h-200/assets/starwars/mandalorian.jpg
```
