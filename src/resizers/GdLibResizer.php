<?php

require_once('Resizer.php');
require_once('ResourceManager.php');
require_once('ProportionSolver.php');

/**
 * Resizes images using GD functions.
 * Class GdLibResizer
 */
class GdLibResizer implements Resizer
{

    /**
     * Scales an image to the specified width and height, then it applies filters if any.
     * It read source image using `$src_image_name` and write the output at the path `$dst_image_name`.
     * In that case, width and height should have the original aspect ratio, otherwise proportion are not
     * preserved.
     * @param $src_image_name string the source image filename.
     * @param $dst_image_name string the destination image filename.
     * @param $width int the destination image width.
     * @param $height int the destination image height.
     * @param $filters array the array of filters (each filter is a string).
     */
    public function scale($src_image_name, $dst_image_name, $width, $height, $filters)
    {
        $size_array = getimagesize($src_image_name);
        if (is_string($width) && strcmp($width, '*') === 0 && is_int($height)) {
            // Calculate the width that maintain the original aspect ratio
            $width = ProportionSolver::solve($size_array[1], $size_array[0], $height);
        } elseif (is_string($height) && strcmp($height, '*') === 0 && is_int($width)) {
            // Calculate the height that maintain the original aspect ratio
            $height = ProportionSolver::solve($size_array[0], $size_array[1], $width);
        }
        // Get the image (it could be png, jpeg or gif)
        $img = ResourceManager::getGdImageResource($src_image_name);
        //Generate destination image
        $dst_img = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $dst_img,
            $img->getResource(),
            0,
            0,
            0,
            0,
            $width,
            $height,
            $size_array[0],
            $size_array[1]
        );
        // Apply filters
        if ($filters !== null) {
            $this->applyFilters($dst_img, $filters);
        }
        // Save resource
        $img->setResource($dst_img);
        $img->write($dst_image_name);
        imagedestroy($img->getResource());
    }

    /**
     * Resize an image to the specified width and height, then it applies filters if any.
     * Proportion are maintained and it will crop the image if it does not fit with desired width and height.
     * It read source image using `$src_image_name` and write the output at the path `$dst_image_name`.
     * @param $src_image_name string the source image filename.
     * @param $dst_image_name string the destination image filename.
     * @param $width int the destination image width.
     * @param $height int the destination image height.
     * @param $filters array the array of filters (each filter is a string).
     */
    public function crop($src_image_name, $dst_image_name, $width, $height, $filters)
    {
        // Get the image (it could be png, jpeg or gif)
        $img = ResourceManager::getGdImageResource($src_image_name);
        $size_array = getimagesize($src_image_name);
        $prop_solver = new ProportionSolver($size_array[0], $size_array[1]);
        // Calculate proportional width and height
        $new_size = $prop_solver->calculateSize($width, $height);
        //Generate destination image
        $dst_img = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $dst_img,
            $img->getResource(),
            $new_size['x'],
            $new_size['y'],
            0,
            0,
            $new_size['new_width'],
            $new_size['new_height'],
            $size_array[0],
            $size_array[1]
        );
        // Apply filters
        if ($filters !== null) {
            $this->applyFilters($dst_img, $filters);
        }
        // Save resource
        $img->setResource($dst_img);
        $img->write($dst_image_name);
        imagedestroy($img->getResource());
    }

    /**
     * Applies all filters to resource image.
     * @param $resource resource the image.
     * @param $filters array the array of filters(es. ['BlackAndWhite', 'FlipHorizontal'])
     */
    private function applyFilters($resource, $filters)
    {
        foreach ($filters as $filter) {
            $this->filter($resource, $filter);
        }
    }

    /**
     * Applies the filter corresponding to the specified filter name.
     * @param $resource resource the image.
     * @param $filter string the filter name.
     */
    private function filter($resource, $filter)
    {
        switch ($filter) {
            case 'BlackAndWhite':
                imagefilter($resource, IMG_FILTER_GRAYSCALE);
                break;
            case 'FlipHorizontal':
                imageflip($resource, IMG_FLIP_HORIZONTAL);
        }
    }
}
