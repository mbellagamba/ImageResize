<?php

class GdJpeg implements Image
{

    private $resource;

    public function __construct($filename)
    {
        $this->resource = imagecreatefromjpeg($filename);
    }

    public function write($filename)
    {
        imagejpeg($this->resource, $filename);
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
