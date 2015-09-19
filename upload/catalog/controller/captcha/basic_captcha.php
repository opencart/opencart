<?php
class ControllerCaptchaBasicCaptcha extends Controller {
	public function index($error = array()) {
		$this->load->language('captcha/basic_captcha');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_captcha'] = $this->language->get('entry_captcha');

		if (isset($error['captcha'])) {
			$data['error_captcha'] = $error['captcha'];
		} else {
			$data['error_captcha'] = '';
		}

		$data['route'] = $this->request->get['route']; 

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/captcha/basic_captcha.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/captcha/basic_captcha.tpl', $data);
		} else {
			return $this->load->view('default/template/captcha/basic_captcha.tpl', $data);
		}
	}

	public function validate() {
		$this->load->language('captcha/basic_captcha');

		if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			return $this->language->get('error_captcha');
		}
	}

	public function captcha() {
		$this->session->data['captcha'] = substr(sha1(mt_rand()), 17, 6);

		$image = imagecreatetruecolor(150, 35);

		$width = imagesx($image);
		$height = imagesy($image);

		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);
		$red = imagecolorallocatealpha($image, 255, 0, 0, 75);
		$green = imagecolorallocatealpha($image, 0, 255, 0, 75);
		$blue = imagecolorallocatealpha($image, 0, 0, 255, 75);

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

		imagejpeg($image);

		imagedestroy($image);
	}
}
