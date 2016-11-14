<?php

require_once('Image.php');

class GdPng implements Image
{

    private $resource;

    public function __construct($filename)
    {
        $this->resource = imagecreatefrompng($filename);
    }

    public function write($filename)
    {
        imagepng($this->resource, $filename);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }
}
