<?php

class GdGif implements Image
{
    private $resource;

    public function __construct($filename)
    {
        $this->resource = imagecreatefromgif($filename);
    }

    public function write($filename)
    {
        imagegif($this->resource, $filename);
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
