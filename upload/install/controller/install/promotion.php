<?php
namespace Opencart\Install\Controller\Install;
class Promotion extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'https://www.opencart.com/index.php?route=api/install');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		$output = curl_exec($curl);

		if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$response = $output;
		} else {
			$response = '';
		}

		curl_close($curl);

		return $response;
	}
}