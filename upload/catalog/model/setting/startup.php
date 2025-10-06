<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Startup
 *
 * Can be called using $this->load->model('setting/startup');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Startup extends \Opencart\System\Engine\Model {
	/**
	 * Get Startups
	 *
	 * Get the record of the startup records in the database.
	 *
	 * @return array<int, array<string, mixed>> startup records
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $startups = $this->model_setting_startup->getStartups();
	 */
	public function getStartups(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}
