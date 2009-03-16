<?php
final class HelperImage {
	static public function resize($filename, $width, $height) {
		if (!file_exists(DIR_IMAGE . $filename)) {
			return;
		} 
	
		$old_image = $filename;
		$new_image = 'cache/' . eregi_replace('\.([a-z]{3,4})', '-' . $width . 'x' . $height . '.jpg', $filename);

		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$image = new Image(DIR_IMAGE . $old_image);
			$image->resize($width, $height);
			$image->save(DIR_IMAGE . $new_image);
		}
	
		if (@$_SERVER['HTTPS'] != 'on') {
			return HTTP_IMAGE . $new_image;
		} else {
			return HTTPS_IMAGE . $new_image;
		}
	}
}
?>