<?php
// Print two names on the picture, which accepted by query string parameters.

$name = "Partagé par " . $_GET['name'];
$setup = $_GET['setup'];

Header ("Content-type: image/jpeg");
$image = imageCreateFromJPEG("partner_banner.jpg");

$pfile = '../img/profile-default.png';
$profile = imagecreatefrompng($pfile);
list($pwidth, $pheight) = getimagesize($pfile);

$color = ImageColorAllocate($image, 255, 255, 255);

imagealphablending($image, true);
imagesavealpha($image, true);

imagecopyresampled($image, $profile, 8, 245, 0, 0, 70, 70, $pwidth, $pheight);

// Write names.
imagettftext($image, 11, 0, 90, 305, $color, 'corbel.ttf', $name);
imagettftext($image, 17, 0, 90, 275, $color, 'corbel.ttf', $setup);

// Return output.
ImageJPEG($image, NULL, 93);
ImageDestroy($image);
?>