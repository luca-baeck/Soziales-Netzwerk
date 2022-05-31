<?php

class ImageUtils{

	public static function convert($location){
		if(file_exists($location)){
			$img = imagecreate(1,1);
			$mime = mime_content_type($location);
			$extension = strtolower(pathinfo($location, PATHINFO_EXTENSION));
			$validImage = true;
			$checkSize = false;
			switch($mime){
				case 'image/jpeg':
					$img = imagecreatefromjpeg($location);
					break;
				case 'image/png':
					$img = imagecreatefrompng($location);
					break;
				case "image/webp":
					$img = imagecreatefromwebp($location);
					$checkSize = true;
					break;
				default:
					$validImage = false;
			}

			if($validImage){
				// code from http://stackoverflow.com/questions/57757439/ddg#58532834 (10.05.2022)
				// however some parts were added afterwards
				$w = imagesx($img);
				$h = imagesy($img);;

				$im = imagecreatetruecolor ($w, $h);
				imageAlphaBlending($im, false);
				imageSaveAlpha($im, true);

				$trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
				imagefilledrectangle($im, 0, 0, $w - 1, $h - 1, $trans);

				imagecopy($im, $img, 0, 0, 0, 0, $w, $h);

				if($checkSize){
					$alt_loc = str_replace($extension, 'tmp', $location);
					imagewebp($im, $alt_loc);
					$size = filesize($location);
					$alt_size = filesize($alt_loc);
					if($alt_size < $size){
						unlink($location);
						rename($alt_loc, $location);
					}else{
						unlink($alt_loc);
					}
				}else{
					imagewebp($im, str_replace($extension, 'webp', $location));
				}
				
				imagedestroy($im);
			}
		}
		
	}

}

?>