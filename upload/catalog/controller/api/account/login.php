<?php
namespace Opencart\Catalog\Controller\Api\Account;
/**
 * Class Login
 *
 * @package Opencart\Catalog\Controller\Api\Account
 */
class Login extends \Opencart\System\Engine\Controller {
	/*
	 * Opencart\Catalog\Controller\Api\Account\Login.Index
	 *
	 * @Example
	 *
	 * $url = 'https://www.yourdomain.com/index.php?route=api/account/login&language=en-gb&store_id=0';
	 *
	 * $request_data = [
	 * 		'username' => 'Default',
	 *		'key'      => ''
	 * ];
	 *
	 * $curl = curl_init();
	 *
	 * curl_setopt($curl, CURLOPT_URL, $url);
	 * curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	 * curl_setopt($curl, CURLOPT_HEADER, false);
	 * curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	 * curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	 * curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	 * curl_setopt($curl, CURLOPT_POST, 1);
	 * curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
	 *
	 * $response = curl_exec($curl);
 	 *
	 * $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	 *
	 * curl_close($curl);
	 *
	 * if ($status == 200) {
	 *		$api_token = json_decode($response, true);
	 *
	 * 		if (isset($api_token['api_token'])) {
	 *
	 * 			// You can now store the session cookie as a var in the your current session or some of persistent storage
	 * 			$session_id = $api_token['api_token'];
	 * 		}
	 * }
	 *
	 * $url = 'http://www.yourdomain.com/opencart-master/upload/index.php?route=api/sale/order.load&language=en-gb&store_id=0&order_id=1';
	 *
	 * $curl = curl_init();
	 *
	 * curl_setopt($curl, CURLOPT_URL, $url);
	 * curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	 * curl_setopt($curl, CURLOPT_HEADER, false);
	 * curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	 * curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	 * curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	 * curl_setopt($curl, CURLOPT_POST, 1);
	 * curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
	 *
	 * // Add the session cookie so we don't have to login again.
	 * curl_setopt($curl, CURLOPT_COOKIE, 'OCSESSID=' . $session_id);
	 *
	 * $response = curl_exec($curl);
	 *
	 * curl_close($curl);
	 *
	 */
	public function index(): void {
		$this->load->language('api/account/login');

		$json = [];

		$this->load->model('account/api');

		// Login with API Key
		if (!empty($this->request->post['username']) && !empty($this->request->post['key'])) {
			$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['key']);
		} else {
			$api_info = [];
		}

		if ($api_info) {
			// Check if IP is allowed
			$ip_data = [];

			$results = $this->model_account_api->getIps($api_info['api_id']);

			foreach ($results as $result) {
				$ip_data[] = trim($result['ip']);
			}

			if (!in_array($this->request->server['REMOTE_ADDR'], $ip_data)) {
				$json['error'] = sprintf($this->language->get('error_ip'), $this->request->server['REMOTE_ADDR']);
			}
		} else {
			$json['error'] = $this->language->get('error_key');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$session = new \Opencart\System\Library\Session($this->config->get('session_engine'), $this->registry);
			$session->start();

			$this->model_account_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

			$session->data['api_id'] = $api_info['api_id'];

			// Create Token
			$json['api_token'] = $session->getId();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
