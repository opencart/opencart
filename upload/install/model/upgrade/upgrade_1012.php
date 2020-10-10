<?php
/**
 * @package		OpenCart
 * @author		OpenCart
 * @copyright	Copyright (c) 2005 - 2021, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

namespace Opencart\Application\Model\Upgrade;
class Upgrade1012 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		//$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "modification`");
		$modification_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "modification'");

		if ($modification_query->num_rows) {
			$this->db->query("DROP TABLE `" . DB_PREFIX . "modification`");
		}
	}
}