<?php
namespace Opencart\Admin\Model\Extension\Opencart\Dashboard;
/**
 * Class Map
 *
 * Can be called from $this->load->model('extension/opencart/dashboard/map');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Dashboard
 */
class Map extends \Opencart\System\Engine\Model {
	/**
	 * Get Total Orders By Country
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $order_total = $this->model_extension_opencart_dashboard_map->getTotalOrdersByCountry();
	 */
	public function getTotalOrdersByCountry(): array {
		$implode = [];

		if (is_array($this->config->get('config_complete_status'))) {
			foreach ($this->config->get('config_complete_status') as $order_status_id) {
				$implode[] = "'" . (int)$order_status_id . "'";
			}
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS `total`, SUM(`o`.`total`) AS `amount`, `c`.`iso_code_2` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "country` `c` ON (`o`.`payment_country_id` = `c`.`country_id`) WHERE `o`.`order_status_id` IN(" . implode(',', $implode) . ") AND `o`.`payment_country_id` != '0' GROUP BY `o`.`payment_country_id`");

			return $query->rows;
		} else {
			return [];
		}
	}
}
