<?php
class ModelUpgrade1001 extends Model {
	public function upgrade() {
		// Update events because we moved the affiliate functions out of the customer class
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'catalog/model/account/affiliate/addAffiliate/after' WHERE `code` = 'activity_affiliate_add'");
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'catalog/model/account/affiliate/editAffiliate/after' WHERE `code` = 'activity_affiliate_edit'");
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'admin/model/sale/return/addReturnHistory/after' WHERE `code` = 'admin_mail_return'");

		$events = array();

		$events[] = array(
			'code'    => 'admin_currency_add',
			'trigger' => 'admin/model/currency/addCurrency/after',
			'action'  => 'event/currency'
		);

		$events[] = array(
			'code'    => 'admin_currency_edit',
			'trigger' => 'admin/model/currency/editCurrency/after',
			'action'  => 'event/currency'
		);

		$events[] = array(
			'code'    => 'admin_setting',
			'trigger' => 'admin/model/setting/setting/editSetting/after',
			'action'  => 'event/currency'
		);

		foreach ($events as $event) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($event['code']) . "'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($event['code']) . "', `trigger` = '" . $this->db->escape($event['trigger']) . "', `action` = '" . $this->db->escape($event['action']) . "', `status` = '1', `sort_order` = '0'");
			}
		}

		if (!is_dir(DIR_STORAGE . 'backup')) {
			mkdir(DIR_STORAGE . 'backup', '0644');

			$handle = fopen(DIR_STORAGE . 'backup/index.html', 'w');

			fclose($handle);
		}

		if (!is_dir(DIR_STORAGE . 'marketplace')) {
			mkdir(DIR_STORAGE . 'marketplace', '0644');

			$handle = fopen(DIR_STORAGE . 'marketplace/index.html', 'w');

			fclose($handle);
		}
	}
}