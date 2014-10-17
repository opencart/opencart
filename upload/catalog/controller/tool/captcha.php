<?php
class ControllerToolCaptcha extends Controller {
	public function index() {
		$this->session->data['captcha'] = substr(sha1(mt_rand()), 17, 6);

		$image = imagecreatetruecolor(150, 35);

		$width = imagesx($image);
		$height = imagesy($image);

		$black = imagecolorallocatealpha($image, 0, 0, 0, 40);
		$white = imagecolorallocate($image, 255, 255, 255);
		$gray = imagecolorallocate($image, 204, 204, 204);
		$litegray = imagecolorallocatealpha($image, 204, 204, 204, 40);
		$red = imagecolorallocatealpha($image, 255, 0, 0, 75);
		$green = imagecolorallocatealpha($image, 0, 255, 0, 75);
		$blue = imagecolorallocatealpha($image, 34, 154, 200, 75);
		$yellow = imagecolorallocatealpha($image, 240, 173, 78, 40);

		imagefilledrectangle($image, 0, 0, $width, $height, $white);

		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $litegray);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $yellow);

		imagefilledrectangle($image, 0, 0, $width, 0, $gray);
		imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $gray);
		imagefilledrectangle($image, 0, 0, 0, $height - 1, $gray);
		imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $gray);

		imagestring($image, 10, intval(($width - (strlen($this->session->data['captcha']) * 9)) / 2), intval(($height - 15) / 2), $this->session->data['captcha'], $black);

		header('Content-type: image/jpeg');

		imagejpeg($image);

		imagedestroy($image);
	}
}
