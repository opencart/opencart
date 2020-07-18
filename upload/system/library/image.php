<?php
/**
 * Image class
 */
class Image {
	private $adaptor;
	private $quality;

	/**
	 * Constructor
	 *
	 * @param string $adaptor Image Library Type
	 *
	 * @param int $quality
	 */
	public function __construct(string $adaptor, int $quality) {
		$class = 'Image\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class();
			$this->quality = $quality;
		} else {
			throw new \RuntimeException('Error: Could not load image adaptor ' . $adaptor . ' library!');
		}
	}

	/**
	 * Set image file
	 *
	 * @param string $file The image file
	 *
	 */
	public function set(string $file) {
		$this->adaptor->set($file);
	}

	/**
	 * Get image file
	 *
	 * @return string
	 *
	 */
	public function getFile() {
		return $this->adaptor->getFile();
	}

	/**
	 * Get an instance of the image class
	 *
	 * @return array
	 *
	 */
	public function getImage() {
		return $this->adaptor->getImage();
	}

	/**
	 * Returns the image width
	 *
	 * @return string
	 */
	public function getWidth() {
		return $this->adaptor->getWidth();
	}

	/**
	 * Returns the image height
	 *
	 * @return string
	 */
	public function getHeight() {
		return $this->adaptor->getHeight();
	}

	/**
	 * Returns the image length in bytes
	 *
	 * @return string
	 */
	public function getBits() {
		return $this->adaptor->getBits();
	}

	/**
	 * Returns the format of the image object
	 *
	 * @return string
	 */
	public function getMime() {
		return $this->adaptor->getMime();
	}

	/**
	 * Resizes the image
	 *
	 * @param int $width
	 * @param int $height
	 */
	public function resize(int $width, int $height) {
		$this->adaptor->resize($width, $height);
	}

	/**
	 * Save image file
	 *
	 * @param string $file The image file
	 * @param int $quality
	 */
	public function save(string $file, int $quality = null) {
		$this->adaptor->save($file, $quality ? : $this->quality);
	}

	/**
	 * Rotates an image
	 *
	 * @param float $degree
	 * @param string $color
	 */
	public function rotate(float $degree, string $color = '#FFFFFF') {
		$this->adaptor->rotate($this->angle($degree), $color);
	}

	/**
	 * Coercion to the same rotation calculation
	 *
	 * The degrees for imagick and gd is difference!
	 * GD > rotate 90 means counter clockwise.
	 * Imagick > rotate 90 means clockwise.
	 * GD 90 = Imagick 270 or Imagick 90 = GD 270.
	 * @link https://www.php.net/manual/ru/imagick.rotateimage.php#119184
	 *
	 * @param float $value
	 *
	 * @return float
	 *
	 */
	protected function angle(float $value) {
		if ($this->adaptor === 'Imagick') {
			if ($value === 0 || $value === 180) {
				return $value;
			}

			if ($value < 0 || $value > 360) {
				$value = (float)90;
			}

			$total_degree = 360;

			return $total_degree - $value;
		}

		return $value;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->adaptor->destroy();
	}
}
