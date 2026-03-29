<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade10
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade10 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "identifier`");

			if (!$query->num_rows) {
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
					$this->db->query("INSERT INTO `" . DB_PREFIX . "identifier` SET `name` = '" . $this->db->escape($identifier['name']) . "',  `code` = '" . $this->db->escape($identifier['code']) . "', `status` = '1'");
				}
			}

			// Drop Fields
			$remove = [
				'sku',
				'upc',
				'ean',
				'jan',
				'isbn'
			];

			foreach ($remove as $field) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = '" . $field . "'");

				if ($query->num_rows) {
					$product_query = $this->db->query("SELECT `product_id`, `" . $field . "` FROM `" . DB_PREFIX . "product` WHERE `" . $field . "` != ''");

					foreach ($product_query->rows as $product) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_code` SET `product_id` = '" . $this->db->escape($product['product_id']) . "', `code` = '" . $this->db->escape($field) . "', `value` = '" . $this->db->escape($product[$field]) . "'");
					}

					$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` DROP `" . $field . "`");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 10, 10, 11);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_11', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
