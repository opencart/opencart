<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade6 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Fixes the serialisation from serialise to json
		try {
			// customer
			$query = $this->db->query("SELECT `customer_id`, `custom_field` FROM `" . DB_PREFIX . "customer` WHERE `custom_field` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
				}
			}

			// customer_activity
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `data` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['data'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `data` = '" . $this->db->escape(json_encode(unserialize($result['data']))) . "' WHERE `customer_activity_id` = '" . (int)$result['customer_activity_id'] . "'");
				}
			}

			// address
			$query = $this->db->query("SELECT `address_id`, `custom_field` FROM `" . DB_PREFIX . "address` WHERE `custom_field` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `address_id` = '" . (int)$result['address_id'] . "'");
				}
			}

			// module
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['setting'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode(unserialize($result['setting']))) . "' WHERE `module_id` = '" . (int)$result['module_id'] . "'");
				}
			}

			// order
			$query = $this->db->query("SELECT `order_id`, `custom_field`, `payment_custom_field`, `shipping_custom_field` FROM `" . DB_PREFIX . "order` WHERE `custom_field` LIKE 'a:%' OR `payment_custom_field` LIKE 'a:%' OR `shipping_custom_field` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
				}

				if (preg_match('/^(a:)/', $result['payment_custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_custom_field` = '" . $this->db->escape(json_encode(unserialize($result['payment_custom_field']))) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
				}

				if (preg_match('/^(a:)/', $result['shipping_custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_custom_field` = '" . $this->db->escape(json_encode(unserialize($result['shipping_custom_field']))) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
				}
			}

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
			$json['text'] = sprintf($this->language->get('text_progress'), 6, 6, 9);

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
