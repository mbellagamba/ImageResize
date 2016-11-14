<?php

require_once('Config.php');
require_once('ImageResizer.php');

$sizes = array('full', 'medium', 'thumbnail');
$files = array('hibiscus.png', 'hibiscus.jpg');
$config = new Config('../config/dev.xml');
$img_resizer = new ImageResizer($config);

foreach ($files as $file) {
    foreach ($sizes as $size) {
        $text = $file . ' resized with ' . $config->get('mode') . ' to ' .
        $config->get($size . '/height') . 'x' . $config->get($size . '/width');
        echo '<h2>' . $text . '</h2>';
        echo '<br/><img src="' . $img_resizer->resize($file, $size) . '" ><br/>';
    }
}
