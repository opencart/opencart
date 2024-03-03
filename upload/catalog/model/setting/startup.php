<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Startup
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Startup extends \Opencart\System\Engine\Model {
	/**
	 * Get Startups
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getStartups(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}
