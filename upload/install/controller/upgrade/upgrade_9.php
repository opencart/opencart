<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade9
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade9 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// order
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'payment_code'");

			if ($query->num_rows) {
				$query = $this->db->query("SELECT `order_id`, `payment_code`, `payment_method`, `shipping_method`, `shipping_code` FROM `" . DB_PREFIX . "order`");

				foreach ($query->rows as $result) {
					if (isset($result['payment_code'])) {
						$payment_method = [
							'name' => $result['payment_method'],
							'code' => $result['payment_code']
						];

						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_custom_field` = '" . $this->db->escape(json_encode($payment_method)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
					}

					if (isset($result['shipping_code'])) {
						$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$result['order_id'] . "' AND `code` = 'shipping'");

						if ($order_total_query->num_rows) {
							$shipping_method = [
								'name' => $result['shipping_method'],
								'code' => $result['shipping_code'],
								'cost' => $order_total_query->row['value'],
								'text' => $result['shipping_method']
							];

							$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_method` = '" . $this->db->escape(json_encode($shipping_method)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
						}
					}
				}

				// Drop Fields
				$remove = [];

				$remove[] = [
					'table' => 'order',
					'field' => 'payment_code'
				];

				// custom_field
				$remove[] = [
					'table' => 'order',
					'field' => 'shipping_code'
				];

				$this->load->model('upgrade/upgrade');

				foreach ($remove as $result) {
					$this->model_upgrade_upgrade->dropField($result['table'], $result['field']);
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 9, 9, 11);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_10', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
