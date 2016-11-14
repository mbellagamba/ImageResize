<?php

require_once('resources/Image.php');
require_once('resources/GdPng.php');
require_once('resources/GdJpeg.php');
require_once('resources/GdGif.php');

/**
 * Retrieves the desired resource.
 * Class ResourceManager
 */
class ResourceManager
{
    /**
     * Returns the Image corresponding to the file extension.
     * @param $path string the image filename.
     * @return Image the image resource.
     */
    public static function getGdImageResource($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $gdImage = new GdJpeg($path);
                break;
            case 'png':
                $gdImage = new GdPng($path);
                break;
            case 'gif':
                $gdImage = new GdGif($path);
                break;
            default:
                $gdImage = null;
        }
        return $gdImage;
    }

    public static function getIMImageResource($path)
    {
        // TODO implement this function and all needed classes for work with ImageMagick
    }
}
