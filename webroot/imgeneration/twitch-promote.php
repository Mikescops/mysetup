<?php
// Print two names on the picture, which accepted by query string parameters.

$name = "Shared by " . $_GET['name'];
$setup = $_GET['setup'];
$id = $_GET['id'];

Header ("Content-type: image/jpeg");
Header ('Content-Disposition: inline; filename="' . $setup . '.jpeg"');
$image = imageCreateFromJPEG("partner_banner.jpg");

$pfile = '../uploads/files/pics/profile_picture_'.$id.'.png';
$profile = imagecreatefromPNG($pfile);
list($pwidth, $pheight) = getimagesize($pfile);

$color = ImageColorAllocate($image, 255, 255, 255);

imagealphablending($image, true);
imagesavealpha($image, true);

imagecopyresampled($image, $profile, 0, 239, 0, 0, 81, 81, $pwidth, $pheight);

// Write names.
if(strlen($setup) > 20){
	$setup = substr($setup, 0, 38);
	$setup1 = wordwrap($setup, 20, "\n");	
	imagettftext($image, 15, 0, 88, 260, $color, 'corbel.ttf', $setup1);
	imagettftext($image, 11, 0, 88, 308, $color, 'corbel.ttf', $name);
}
else{
	imagettftext($image, 17, 0, 88, 277, $color, 'corbel.ttf', $setup);
	imagettftext($image, 11, 0, 88, 300, $color, 'corbel.ttf', $name);
}

// Return output.
ImageJPEG($image, NULL, 93);
ImageDestroy($image);
?>