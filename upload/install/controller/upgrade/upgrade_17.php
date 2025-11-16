<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade17
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
			$json['text'] = sprintf($this->language->get('text_patch'), 17, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_18', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
