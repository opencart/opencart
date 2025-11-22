<?php
namespace Opencart\Install\Controller\Install;
/**
 * Class Promotion
 *
 * Can be loaded using $this->load->controller('install/promotion');
 *
 * @package Opencart\Install\Controller\Install
 */
class Promotion extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'https://www.opencart.com/index.php?route=api/install');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		$output = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status == 200) {
			$response = $output;
		} else {
			$response = '';
		}

		return $response;
	}
}
