<?php
namespace Opencart\Catalog\Model\Setting;
class Startup extends \Opencart\System\Engine\Model {
	function getStartups() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}