<?php
class ModelUpgrade1001 extends Model {
	public function upgrade() {
		// Update events because we moved the affiliate functions out of the customer class
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'catalog/model/account/affiliate/addAffiliate/after' WHERE `code` = 'activity_affiliate_add'");
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'catalog/model/account/affiliate/editAffiliate/after' WHERE `code` = 'activity_affiliate_edit'");
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'admin/model/sale/return/addReturnHistory/after' WHERE `code` = 'admin_mail_return'");

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