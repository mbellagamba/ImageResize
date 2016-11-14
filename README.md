# Image Resize

This is a demo project for resizing images using parameters in an XML configuration file.

## Project structure
``` XML
<folder name="ImageResize" description="">
  <folder name="config" description="Contains configuration XML files." ></folder>
  <folder name="src" description="The main source folder.">
    <folder name="archive" description="The archive of images at full resolution." ></folder>
    <folder name="cache" description="The folder of resized images." ></folder>
    <folder name="archive" description="The code of resizers.">
      <folder name="resources" description="The resource that each resizer should be able to resize."></folder>
    </folder>
  </folder>
</folder>
```
