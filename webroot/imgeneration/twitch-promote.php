<?php

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
    $name = 'Shared by ' . $_GET['name'];
	$setup = $_GET['setup'];

    $pfile = '../uploads/files/pics/profile_picture_' . $_GET['id'] . '.png';
    $profile = ImageCreateFromPNG($pfile);
    list($pwidth, $pheight) = GetImageSize($pfile);

	$image = ImageCreateFromJPEG("partner_banner.jpg");
    ImageAlphaBlending($image, true);
    ImageSaveAlpha($image, true);
    ImageCopyResampled($image, $profile, 0, 239, 0, 0, 81, 81, $pwidth, $pheight);

    $color = ImageColorAllocate($image, 255, 255, 255);

    // Write names.
    if(strlen($setup) > 20)
    {
        $setup = wordwrap(substr($setup, 0, 38), 20, "\n");
        ImageTTFText($image, 15, 0, 88, 260, $color, './corbel.ttf', $setup);
        ImageTTFText($image, 11, 0, 88, 308, $color, './corbel.ttf', $name);
    }

    else
    {
        ImageTTFText($image, 17, 0, 88, 277, $color, './corbel.ttf', $setup);
        ImageTTFText($image, 11, 0, 88, 300, $color, './corbel.ttf', $name);
	}

    header('Content-type: image/jpeg');
    header('Content-Disposition: inline; filename="' . $setup . '.jpeg"');

	// Return output.
	ImageJPEG($image, NULL, 93);
	ImageDestroy($image);
}

else
{
	header('location: ../');
}
