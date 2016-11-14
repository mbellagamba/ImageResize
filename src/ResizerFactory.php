<?php

require_once('resizers/Resizer.php');
require_once('resizers/GdLibResizer.php');
require_once('resizers/ImageMagickResizer.php');

class ResizerFactory
{
    /**
     * Get the resizing library using its name. Available Resizers are GDLIB and IMAGEMAGICK.
     * Adding a new resizing library is easy: just add its name in that swith and make a new class
     * implementing the Resizer interface.
     * @param $name string the resizer name.
     * @return Resizer the resizing library.
     */
    public static function getResizer($name)
    {
        switch ($name) {
            case 'GDLIB':
                return new GdLibResizer();
            case 'IMAGEMAGICK':
                return new ImageMagickResizer();
            default:
                die('Invalid mode');
        }
    }
}
