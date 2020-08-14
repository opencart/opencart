<?php
class ModelToolImage extends Model {
	public function link($filename) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '.' . $extension;

		$path = dirname($image_new);

		if (!is_dir(DIR_IMAGE . $path)) {
			@mkdir(DIR_IMAGE . $path, 0777, true);
		}

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
		}

		return $this->getUrl($image_new);
	}

	public function resize($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if ($width == 0 && $height > 0 && $height_orig > 0) {
				$width = $height * $width_orig / $height_orig;
			} elseif ($height == 0 && $width > 0 && $height_orig > 0) {
				$height = $width * $height_orig / $width_orig;
			}

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF)) || $width_orig == 0 || $height_orig == 0 || $width == 0 || $height == 0) {
				return $this->link($filename);
			}

			$path = dirname($image_new);

			if (!is_dir(DIR_IMAGE . $path)) {
				@mkdir(DIR_IMAGE . $path, 0777, true);
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new \Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		return $this->getUrl($image_new);
	}

	public function crop($filename, $width, $height) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '-crop.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if ($width == 0 && $height > 0 && $height_orig > 0) {
				$width = $height * $width_orig / $height_orig;
			} elseif ($height == 0 && $width > 0 && $height_orig > 0) {
				$height = $width * $height_orig / $width_orig;
			}
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF)) || $width_orig == 0 || $height_orig == 0 || $width == 0 || $height == 0) {
				return $this->link($filename);
			}

			$path = dirname($image_new);

			if (!is_dir(DIR_IMAGE . $path)) {
				@mkdir(DIR_IMAGE . $path, 0777, true);
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new \Image(DIR_IMAGE . $image_old);

				if ($width_orig / $width > $height_orig / $height) {
					$razn = ($width_orig - $height_orig * $width / $height) / 2;
					$image->crop($razn, 0, $width_orig - $razn, $height_orig);
				} elseif ($width_orig / $width < $height_orig / $height) {
					$razn = ($height_orig - $width_orig * $height / $width) / 2;
					$image->crop(0, $razn, $width_orig, $height_orig - $razn);
				}

				$image->resize($width, $height);

				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		return $this->getUrl($image_new);
	}

	public function getUrl($filename) {
		return $this->config->get('config_url') . 'image/' . str_replace(' ', '%20', $filename);
	}
}