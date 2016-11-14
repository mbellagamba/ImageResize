<?php

require_once('ResizerFactory.php');

/**
 * Resizes images using the configuration object passed to the constructor.
 * The `mode` configuration defines which library will be used. In this example, GDLIB
 * is the unique library working.
 * Class ImageResizer
 */
class ImageResizer
{
    /** @var Config the configuration object. */
    private $config;
    /** @var Resizer the object able to resize images. The ResizerFactory instantiates it. */
    private $resizer;

    /**
     * ImageResizer constructor. Injects the configuration object and create the resizer,
     * depending on the configuration.
     * @param Config $config the configuration object.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->resizer = ResizerFactory::getResizer($config->get('mode'));
    }

    /**
     * Resizes the source image to the specified size, if available in the configuration.
     * It returns the filename of the resized image.
     * @param $img_filename string the filename of the image to resize.
     * @param $size string the desired size (es. thumbnail, medium, full)
     * @return string the resized image filename.
     */
    public function resize($img_filename, $size)
    {
        $height = $this->config->get($size . '/height');
        $width = $this->config->get($size . '/width');
        $crop = $this->config->get($size . '/crop');
        $filters = $this->config->get($size . '/filters');
        $src_image_name = $this->config->get('archive') . $img_filename;
        $dst_image_name = $this->config->get('imageCache') . $size . $img_filename;
        if ($this->isResizeNeeded($src_image_name, $dst_image_name)) {
            if (is_int($height) && is_int($width) && $crop) {
                $this->resizer->crop($src_image_name, $dst_image_name, $width, $height, $filters);
            } elseif ($height !== null && $width !== null) {
                $this->resizer->scale($src_image_name, $dst_image_name, $width, $height, $filters);
            } else {
                die('Error resizing image');
            }
        }
        return $dst_image_name;
    }

    /**
     * Check if the resized image exists or if the source image is modified after the resized image
     * is created. In both cases, it returns true.
     * @param string $src_image_name the source image filename.
     * @param string $dst_image_name the resized image filename.
     * @return bool true if there is need to generate a new resized image.
     */
    private function isResizeNeeded($src_image_name, $dst_image_name)
    {
        return !file_exists($dst_image_name) ||                  // File not exists yet
        filemtime($src_image_name) > filemtime($dst_image_name); // Source image is modified after that resized
    }
}
