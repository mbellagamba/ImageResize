<?php

/**
 * A new resizing library should implement this interface.
 * Interface Resizer
 */
interface Resizer
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
    public function scale($src_image_name, $dst_image_name, $width, $height, $filters);

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
    public function crop($src_image_name, $dst_image_name, $width, $height, $filters);
}
