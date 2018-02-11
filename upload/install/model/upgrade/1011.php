<?php
/**
 * @package		OpenCart
 * @author		OpenCart
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Upgrade file for updating setting table columns
* 
*/
class ModelUpgrade1011 extends Model {
	public function upgrade() {
		//get all setting columns from extension table
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension`");	

		foreach ($query->rows as $extension) {
			//get all setting from setting table
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE code = '" . $extension['code'] . "'");	
			
			if ($query->num_rows) {
				foreach ($query->rows as $result) {
					//update old column name to adding prefix before the name
					if ($result['code'] == $extension['code'] && $result['code'] !=  $extension['type'] . "_" . $extension['code'] && $extension['type'] != "theme") {
						$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = '" . $this->db->escape($extension['type'] . "_" . $extension['code']) . "', `key` = '" . $this->db->escape($extension['type'] . "_" . $result['key']) . "', `value` = '" . $this->db->escape($result['value']) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
					}
				}
			}	
		}
	}
}
