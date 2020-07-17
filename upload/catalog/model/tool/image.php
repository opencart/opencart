<?php
class ModelToolImage extends Model {
	/**
	 * @param mixed ...$arguments
	 *
	 * @return string|void
	 */
	public function resize(...$arguments) {
		if (!is_file(DIR_IMAGE . $arguments[0]) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $arguments[0])), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($arguments[0], PATHINFO_EXTENSION);

		$image_old = $arguments[0];
		$image_new = 'cache/' . utf8_substr($arguments[0], 0, utf8_strrpos($arguments[0], '.')) . '-' . (int)$arguments[1] . 'x' . (int)$arguments[2] . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			[$width_orig, $height_orig, $image_type] = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return $this->config->get('config_url') . 'image/' . $image_old;
			}

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig !== $arguments[1] || $height_orig !== $arguments[2]) {
				$this->image->set(DIR_IMAGE . $image_old);
				$this->image->resize($arguments[1], $arguments[2]);
				$this->image->save(DIR_IMAGE . $image_new, $arguments[3] ?? null);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

		return $this->config->get('config_url') . 'image/' . $image_new;
	}
}
