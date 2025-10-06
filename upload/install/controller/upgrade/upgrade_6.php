<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade6
 *
 * Extension setting changes and default values
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade6 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Adds any missing setting keys or default values that need changing or removed
		try {
			// Get all setting columns from extension table
			$this->load->model('upgrade/upgrade');

			$extensions = $this->model_upgrade_upgrade->getRecords('extension');

			foreach ($extensions as $extension) {
				//get all setting from setting table
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->db->escape($extension['code']) . "'");

				if ($query->num_rows) {
					foreach ($query->rows as $result) {
						//update old column name to adding prefix before the name
						if ($result['code'] == $extension['code'] && $result['code'] != $extension['type'] . '_' . $extension['code']) {
							$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = '" . $this->db->escape($extension['type'] . '_' . $extension['code']) . "', `key` = '" . $this->db->escape($extension['type'] . '_' . $result['key']) . "', `value` = '" . $this->db->escape($result['value']) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
						}
					}
				}
			}

			// List of default extension to add the opencart extension code to.
			$default = [
				'cod',
				'shipping',
				'sub_total',
				'tax',
				'total',
				'banner',
				'credit',
				'flat',
				'handling',
				'low_order_fee',
				'coupon',
				'category',
				'account',
				'reward',
				'free_checkout',
				'featured',
				'basic',
				'activity',
				'sale',
				'order',
				'online',
				'map',
				'customer',
				'chart',
				'sale_coupon',
				'customer_search',
				'customer_transaction',
				'product_purchased',
				'product_viewed',
				'sale_return',
				'sale_order',
				'sale_shipping',
				'sale_tax',
				'customer_activity',
				'customer_order',
				'customer_reward',
				'ecb'
			];

			$extensions = $this->model_upgrade_upgrade->getRecords('extension');

			foreach ($extensions as $extension) {
				if (!$extension['extension'] && in_array($extension['code'], $default)) {
					$this->db->query("UPDATE `" . DB_PREFIX . "extension` SET `extension` = 'opencart' WHERE `code` = '" . $this->db->escape($extension['code']) . "'");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 6, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_7', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
