<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Api extends \Opencart\System\Engine\Controller {
	/**
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (substr($route, 0, 4) == 'api/') {
			$status = true;

			$required = [
				'route',
				'username',
				'store_id',
				'language',
				'time',
				'signature'
			];

			foreach ($required as $key) {
				if (!isset($this->request->get[$key])) {
					$status = false;
				}
			}

			if ($status) {
				$this->load->model('user/api');

				$api_info = $this->model_user_api->getApiByUsername((string)$this->request->get['username']);

				if ($api_info) {
					// Check if IP is allowed
					$ip_data = [];

					$results = $this->model_account_api->getIps($api_info['api_id']);

					foreach ($results as $result) {
						$ip_data[] = trim($result['ip']);
					}

					if (!in_array(oc_get_ip(), $ip_data)) {
						$status = false;
					}
				} else {
					$status = false;
				}

				$time = $this->request->get['time'];

				$time_start = time() - 450;
				$time_end = time() + 450;

				if ($time < $time_start && $time > $time_end) {
					$status = false;
				}
			}

			if ($status) {
				$string  = (string)$route . "\n";
				$string .= $api_info['username'] . "\n";
				$string .= (string)$this->request->server['HTTP_HOST'] . "\n";
				$string .= (int)$this->request->get['store_id'] . "\n";
				$string .= (string)$this->request->get['language'] . "\n";
				$string .= json_encode($this->request->post) . "\n";
				$string .= $time . "\n";

				if ($this->request->get['signature'] != base64_encode(hash_hmac('sha1', $string, $api_info['key'], 1))) {
					$status = false;
				}
			}

			if (!$status) {
				return new \Opencart\System\Engine\Action('startup/api.permission');
			}
		}

		return null;
	}

	public function permission() {
		$this->language->load('error/permission');

		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 403 Forbidden');
		$this->response->setOutput(json_encode(['error' => $this->language->get('text_error')]));
	}
}
