<?php
namespace Opencart\Admin\Model\Extension\Opencart\Dashboard;
class Map extends \Opencart\System\Engine\Model {
	public function getTotalOrdersByCountry(): array {
		$implode = [];

		if (is_array($this->config->get('config_complete_status'))) {
			foreach ($this->config->get('config_complete_status') as $order_status_id) {
				$implode[] = "'" . (int)$order_status_id . "'";
			}
		}

		if ($implode) {
			if ($this->config->get('config_checkout_payment_address')) {
				$query = $this->db->query("SELECT COUNT(*) AS `total`, SUM(o.`total`) AS `amount`, c.`iso_code_2` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.`payment_country_id` = c.`country_id`) WHERE o.`order_status_id` IN(" . implode(',', $implode) . ") GROUP BY o.`payment_country_id`");
			} else {
				$query = $this->db->query("SELECT COUNT(*) AS `total`, SUM(o.`total`) AS `amount`, c.`iso_code_2` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.`shipping_country_id` = c.`country_id`) WHERE o.`order_status_id` IN(" . implode(',', $implode) . ") GROUP BY o.`shipping_country_id`");
			}
			
			return $query->rows;
		} else {
			return [];
		}
	}
}
