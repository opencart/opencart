<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade16
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade16 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$this->load->model('upgrade/upgrade');

			if ($this->model_upgrade_upgrade->hasField('subscription', 'currency')) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` DROP COLUMN `currency_code`");

				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` CHANGE COLUMN `currency` `currency_code` VARCHAR(3) NOT NULL AFTER `language`");
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 16, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_17', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
