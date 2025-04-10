<?php
// Generate English flag image
header('Content-Type: image/png');
$width = 32;
$height = 32;

// Create image
$image = imagecreatetruecolor($width, $height);

// Colors
$blue = imagecolorallocate($image, 0, 36, 125); // #00247D
$white = imagecolorallocate($image, 255, 255, 255); // #FFFFFF
$red = imagecolorallocate($image, 207, 20, 43); // #CF142B

// Fill background with blue
imagefill($image, 0, 0, $blue);

// Draw white cross (horizontal)
imagefilledrectangle($image, 0, 12, $width, 20, $white);

// Draw white cross (vertical)
imagefilledrectangle($image, 12, 0, 20, $height, $white);

// Draw red cross (horizontal)
imagefilledrectangle($image, 0, 14, $width, 18, $red);

// Draw red cross (vertical)
imagefilledrectangle($image, 14, 0, 18, $height, $red);

// Draw diagonal white lines (simplified)
imagesetthickness($image, 6);
imageline($image, 0, 0, $width, $height, $white);
imageline($image, $width, 0, 0, $height, $white);

// Draw diagonal red lines (simplified)
imagesetthickness($image, 2);
imageline($image, 0, 0, $width, $height, $red);
imageline($image, $width, 0, 0, $height, $red);

// Output image
imagepng($image);
imagedestroy($image);
?> 