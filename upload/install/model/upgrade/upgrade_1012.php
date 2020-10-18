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
namespace Install\Model\Upgrade;
class Upgrade1012 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Pull all active languages.
		$languages = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `status` = '1'");
		
		if ($languages->num_rows) {
			$currencies = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency` WHERE `status` = '1'");
			
			if ($currencies->num_rows) {
				// Settings
				$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_country_id'");
				
				if ($query->num_rows) {
					foreach ($currencies->rows as $currency) {
						foreach ($languages->rows as $language) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "currency_description` SET `title` = '" . $this->db->escape($currency['title']) . "', `currency_id` = '" . (int)$currency['currency_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `country_id` = '" . (int)$query->row['value'] . "', `push` = '0'");
						}
					}
				}
				
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "currency` DROP COLUMN `title`");
			}
		}
	}
}
