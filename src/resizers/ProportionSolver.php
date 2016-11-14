<?php

/**
 * Define correct proportion and aspect ratio for an image.
 * Class ProportionSolver
 */
class ProportionSolver
{

    /** @var  int the original image width */
    private $width;
    /** @var  int the original image height */
    private $height;

    /**
     * ProportionSolver constructor.
     * @param $width int the image width.
     * @param $height int the image height.
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Calculate the size that fits with that passed as parameters, but which does not break up
     * image proportions. It returns an array with new_width and new_height. This array has also
     * information about how many pixel should be cropped to center the image.
     * @param $crop_width
     * @param $crop_height
     * @return array
     */
    public function calculateSize($crop_width, $crop_height)
    {
        $original_aspect = $this->width / $this->height;
        $crop_aspect = $crop_width / $crop_height;

        if ($original_aspect >= $crop_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_width = $this->width / ($this->height / $crop_height);
            $new_height = $crop_height;
        } else {
            // If the thumbnail is wider than the image
            $new_width = $crop_width;
            $new_height = $this->height / ($this->width / $crop_width);
        }

        return array(
            'x' => 0 - ($new_width - $crop_width) / 2, // Center the image horizontally
            'y' => 0 - ($new_height - $crop_height) / 2, // Center the image vertically
            'new_width' => $new_width,
            'new_height' => $new_height
            );
    }

    /**
     * Solve the following proportion.
     * $first : $second = $third : $result
     * @param $first
     * @param $second
     * @param $third
     * @return int the proportion result.
     */
    public static function solve($first, $second, $third)
    {
        return intval(round($second * $third / $first));
    }
}
