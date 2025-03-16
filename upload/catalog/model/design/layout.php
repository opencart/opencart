<?php
namespace Opencart\Catalog\Model\Design;
/**
 * Class Layout
 *
 * Can be called using $this->load->model('design/layout');
 *
 * @package Opencart\Catalog\Model\Design
 */
class Layout extends \Opencart\System\Engine\Model {
	/**
	 * Get Layout
	 *
	 * Get the record of the layout record in the database.
	 *
	 * @param string $route
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $layout_id = $this->model_design_layout->getLayout($route);
	 */
	public function getLayout(string $route): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE '" . $this->db->escape($route) . "' LIKE `route` AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `route` DESC LIMIT 1");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Modules
	 *
	 * Get the record of the layout module records in the database.
	 *
	 * @param int    $layout_id
	 * @param string $position
	 *
	 * @return array<int, array<string, mixed>> module records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('design/layout');
	 *
	 * $modules = $this->model_design_banner->getModules($layout_id, $position);
	 */
	public function getModules(int $layout_id, string $position): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_module` WHERE `layout_id` = '" . (int)$layout_id . "' AND `position` = '" . $this->db->escape($position) . "' ORDER BY `sort_order`");

		return $query->rows;
	}
}
