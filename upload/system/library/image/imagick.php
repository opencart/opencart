<?php
namespace Image;
class Imagick {
	private $file;
	private $image;
	private $width;
	private $height;
	private $bits;
	private $mime;

	public function __construct() {
		if (!extension_loaded('imagick')) {
			exit('Error: PHP Imagick is not installed!');
		}
	}

	public function set(string $file): void {
		if (file_exists($file)) {
			$this->image = new \Imagick();
			$this->image->setBackgroundColor(new \ImagickPixel('transparent'));
			$this->image->readImage($file);

			$this->width = $this->image->getImageWidth();
			$this->height = $this->image->getImageHeight();
			$this->bits = $this->image->getImageLength();
			$this->mime = $this->image->getFormat();
		} else {
			exit('Error: Could not load image ' . $file . '!');
		}
	}

	/**
	 * Returns an image file
	 *
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Returns the Imagick object
	 *
	 * @return array
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Returns the image width
	 *
	 * @return string
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Returns the image height
	 *
	 * @return string
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Returns the image length in bytes
	 *
	 * @return string
	 */
	public function getBits() {
		return $this->bits;
	}

	/**
	 * Returns the format of the Imagick object
	 *
	 * @return string
	 */
	public function getMime() {
		return $this->mime;
	}

	/**
	 * Saves the image
	 *
	 * @param string $file
	 * @param int $quality
	 */
	public function save(string $file, int $quality = 90) {
		$this->image->setImageCompressionQuality($quality);
		$this->image->setImageFormat($this->mime);
		$this->image->writeImage($file);
	}

	/**
	 * Resizes the image
	 *
	 * @param int $width
	 * @param int $height
	 * @param bool $default
	 * @param string $background
	 */
	public function resize(int $width = 0, int $height = 0, $default = false, $background = '#FFFFFF') {
		if (!$this->width || !$this->height) {
			return;
		}

		switch ($default) {
			case 'w':
				$height = $width;
				break;
			case 'h':
				$width = $height;
			break;
		}

		$this->image->resizeImage($width, $height, false, 1, true);

		if ($width === $height && $this->width !== $this->height) {
			$original = $this->image;

			$this->image = new \Imagick();
			$this->image->newImage($width, $height, $background);

			$x = round(($width - $original->getImageWidth()) / 2);
			$y = round(($height - $original->getImageHeight()) / 2);

			$this->image->compositeImage($original, \Imagick::COMPOSITE_OVER, $x, $y);
		}

		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * Overlay watermark on image
	 *
	 * @param $file
	 * @param string $position
	 */
	public function watermark($file, $position = 'bottomright') {
		$this->set($file);

		switch ($position) {
			case 'overlay':
				for ($w = 0; $w < $this->width; $w += $this->image->getImageWidth()) {
					for ($h = 0; $h < $this->height; $h += $this->image->getImageHeight()) {
						$this->image->compositeImage($this->image, \Imagick::COMPOSITE_OVER, $w, $h);
					}
				}
			break;
			case 'topleft':
				$this->image->compositeImage($this->image, \Imagick::COMPOSITE_OVER, 0, 0);
			break;
			case 'topright':
				$this->image->compositeImage($this->image, \Imagick::COMPOSITE_OVER, $this->width - $this->image->getImageWidth(), 0);
			break;
			case 'bottomleft':
				$this->image->compositeImage($this->image, \Imagick::COMPOSITE_OVER, 0, $this->height - $this->image->getImageHeight());
			break;
			case 'bottomright':
				$this->image->compositeImage($this->image, \Imagick::COMPOSITE_OVER, $this->width - $this->image->getImageWidth(), $this->height - $this->image->getImageHeight());
			break;
		}
	}

	/**
	 * Extracts a region of the image
	 *
	 * @param int $top_x
	 * @param int $top_y
	 * @param int $bottom_x
	 * @param int $bottom_y
	 */
	public function crop(int $top_x, int $top_y, int $bottom_x, int $bottom_y) {
		$this->image->cropImage($top_x, $top_y, $bottom_x, $bottom_y);

		$this->width = $bottom_x - $top_x;
		$this->height = $bottom_y - $top_y;
	}

	/**
	 * Rotates an image
	 *
	 * @param float $degree
	 * @param string $color
	 */
	public function rotate(float $degree, string $color = 'transparent') {
		$this->image->rotateimage(new \ImagickPixel($color), $degree);
	}

	/**
	 * Clears all resources associated to Imagick object
	 */
	public function destroy() {
		if (is_resource($this->image)) {
			$this->image->clear();
		}
	}
}
