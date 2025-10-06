<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade7
 *
 * Convert any old versions of opencart that used php serialised function to store data.
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade7 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Fixes the serialisation from serialise to json
		try {
			// module
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['setting'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode(unserialize($result['setting']))) . "' WHERE `module_id` = '" . (int)$result['module_id'] . "'");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 7, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_8', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}