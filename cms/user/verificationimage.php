<?php session_name("vtp");
session_start();
// -----------------------------------------
//  The Web Help .com
// -----------------------------------------

header('Content-type: image/jpeg');

$width = 80;
$height = 22;

$my_image = imagecreatetruecolor($width, $height);

imagefill($my_image, 0, 0, 0xFFFFFF);

// add noise
for ($c = 0; $c < 150; $c++){
	$x = rand(0,$width-1);
	$y = rand(0,$height-1);
	imagesetpixel($my_image, $x, $y, 0x000000);
	}

$x = rand(1,8);
$y = rand(1,8);

$rand_string = $_SESSION['formverification'];
imagestring($my_image, 5, $x, $y, $rand_string, 0x000000);
imagejpeg($my_image);
imagedestroy($my_image);
?>
