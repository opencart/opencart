<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade13
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade13 extends \Opencart\System\Engine\Controller {
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

			$identifiers = $this->model_upgrade_upgrade->getRecords('identifier');

			if (!$identifiers) {
				$identifiers = [];

				$identifiers[] = [
					'name' => 'Stock Keeping Unit',
					'code' => 'SKU'
				];

				$identifiers[] = [
					'name' => 'Universal Product Code',
					'code' => 'UPC'
				];

				$identifiers[] = [
					'name' => 'European Article Number',
					'code' => 'EAN'
				];

				$identifiers[] = [
					'name' => 'Japanese Article Number',
					'code' => 'JAN'
				];

				$identifiers[] = [
					'name' => 'International Standard Book Number',
					'code' => 'ISBN'
				];

				$identifiers[] = [
					'name' => 'Manufacturer Part Number',
					'code' => 'MPN'
				];

				foreach ($identifiers as $identifier) {
					$identifier_id = $this->model_upgrade_upgrade->addRecord('identifier', $identifier);

					$code = strtolower($identifier['code']);

					if ($this->model_upgrade_upgrade->hasField('product', $code)) {
						$product_query = $this->db->query("SELECT `product_id`, `" . $this->db->escape($code) . "` FROM `" . DB_PREFIX . "product` WHERE `" . $this->db->escape($identifier['code']) . "` != ''");

						foreach ($product_query->rows as $product) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "product_code` SET `product_id` = '" . $this->db->escape($product['product_id']) . "', `identifier_id` = '" . (int)$identifier_id . "', `value` = '" . $this->db->escape($product[$code]) . "'");
						}

						$this->model_upgrade_upgrade->dropField('product', $code);
					}
				}
			}

			if ($this->model_upgrade_upgrade->hasField('product_code', 'code')) {
				$product_codes = $this->model_upgrade_upgrade->getRecords('product_code');

				foreach ($product_codes as $product_code) {
					$identifier_query = $this->db->query("SELECT `identifier_id` FROM `" . DB_PREFIX . "identifier` WHERE code = '" . $this->db->escape($product_code['code']) . "'");

					if ($identifier_query->num_rows) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_code` SET `identifier_id` = '" . (int)$identifier_query->row['identifier_id'] . "' WHERE `product_code_id` = '" . (int)$product_code['product_code_id'] . "'");
					}
				}

				$this->model_upgrade_upgrade->dropField('product_code', 'code');
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 13, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_14', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
