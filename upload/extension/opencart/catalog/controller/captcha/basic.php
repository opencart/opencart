<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Captcha;
/**
 * Class Basic
 *
 * @package
 */
class Basic extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/opencart/captcha/basic');

		$data['route'] = (string)$this->request->get['route'];

		$this->session->data['captcha'] = substr(oc_token(100), rand(0, 94), 6);

		return $this->load->view('extension/opencart/captcha/basic', $data);
	}

	/**
	 * @return string
	 */
	public function validate(): string {
		$this->load->language('extension/opencart/captcha/basic');

		if (!isset($this->session->data['captcha']) || !isset($this->request->post['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			return $this->language->get('error_captcha');
		} else {
			return '';
		}
	}

	/**
	 * @return void
	 */
	public function captcha(): void {
		$image  = imagecreatetruecolor(150, 35);

		$width  = imagesx($image);
		$height = imagesy($image);

		$black  = imagecolorallocate($image, 0, 0, 0);
		$white  = imagecolorallocate($image, 255, 255, 255);
		$red    = imagecolorallocatealpha($image, 255, 0, 0, 75);
		$green  = imagecolorallocatealpha($image, 0, 255, 0, 75);
		$blue   = imagecolorallocatealpha($image, 0, 0, 255, 75);

		imagefilledrectangle($image, 0, 0, $width, $height, $white);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue);
		imagefilledrectangle($image, 0, 0, $width, 0, $black);
		imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $black);
		imagefilledrectangle($image, 0, 0, 0, $height - 1, $black);
		imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $black);

		imagestring($image, 10, intval(($width - (strlen($this->session->data['captcha']) * 9)) / 2), intval(($height - 15) / 2), $this->session->data['captcha'], $black);

		header('Content-type: image/jpeg');
		header('Cache-Control: no-cache');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

		imagejpeg($image);

		imagedestroy($image);
		exit();
	}
}
