<?php
	define('PHOTO_DIR', '/var/www/html/fotoinbeeld/pieter');

	// Content type
	header('Content-Type: image/jpeg');
	$filename = PHOTO_DIR . $_GET['q'];

	$max = isset($_GET['max']) ? $_GET['max'] : 8000;

	if(count($_REQUEST) == 0) 
	{
		echo file_get_contents($filename);
		die();
	}
	// Get new dimensions
	list($width, $height) = getimagesize($filename);

	$swidth = isset($_GET['width'])? $_GET['width'] : $width;
	$sheight = isset($_GET['height'])? $_GET['height'] : $height;

	$mwidth = min($swidth, $width);
	$mheight = min($sheight, $height);

	if(!isset($_GET['max']))
	{
		$horw = ($mwidth - $width) - ($mheight - $height) > 0;
		if($horw)
		{
			// Portrait
			$new_height = (int)$mheight;
			$new_width =(int)($mheight / $height) * $width;
		}
		else
		{
			$new_height = (int)($mwidth / $width) * $height;
			$new_width = (int)$mwidth;
		}
	}
	elseif($width > $height)
	{
		$new_width = (int)$max;
		$new_height = (int)($max / $width * $height);
	}
	else
	{
		$new_width = (int)(0.9*$max / $height * $width);
		$new_height = (int)(0.9*$max);
	}
	
	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	// Output
	imagejpeg($image_p, null, 70);

?>
