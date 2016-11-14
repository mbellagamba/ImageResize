<?php

/**
 * It represents an image, new file type should implements this interface.
 * Interface Image
 */
interface Image
{
    /**
     * Instatiates the image from its filename.
     * Image constructor.
     * @param $filename string the image filename.
     */
    public function __construct($filename);

    /**
     * Saves the image at the path specified by parameter.
     * @param $filename string the filename where image resource should be saved.
     */
    public function write($filename);

    /**
     * Return the representation of the image.
     * @return mixed the image resource.
     */
    public function getResource();

    /**
     * Sets a new representation of the image.
     * @param $resource mixed the image resource.
     */
    public function setResource($resource);
}
