<?php
// Generate Vietnamese flag image
header('Content-Type: image/png');
$width = 32;
$height = 32;

// Create image
$image = imagecreatetruecolor($width, $height);

// Colors
$red = imagecolorallocate($image, 218, 37, 29); // #DA251D
$yellow = imagecolorallocate($image, 255, 255, 0); // #FFFF00

// Fill background with red
imagefill($image, 0, 0, $red);

// Draw yellow star
$centerX = $width / 2;
$centerY = $height / 2;
$spikes = 5;
$outerRadius = 10;
$innerRadius = 4;

// Calculate star points
$points = array();
$rot = M_PI / 2 * 3;
$step = M_PI / $spikes;

for($i = 0; $i < $spikes * 2; $i++) {
    $r = ($i & 1) ? $innerRadius : $outerRadius;
    $points[] = $centerX + $r * cos($rot);
    $points[] = $centerY + $r * sin($rot);
    $rot += $step;
}

// Draw star
imagefilledpolygon($image, $points, $spikes * 2, $yellow);

// Output image
imagepng($image);
imagedestroy($image);
?> 