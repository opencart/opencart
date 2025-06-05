<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade16
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade17 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// user_group
			$query = $this->db->query("SELECT `user_group_id`, `permission` FROM `" . DB_PREFIX . "user_group`");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['permission'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode(unserialize($result['permission']))) . "' WHERE `user_group_id` = '" . (int)$result['user_group_id'] . "'");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
