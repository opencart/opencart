<?php
namespace Install\Model\Upgrade;
class Upgrade1010 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Add missing core events
		$events = [];

		$events[] = [
			'code'    => 'activity_customer_add',
			'trigger' => 'catalog/model/account/customer/addCustomer/after',
			'action'  => 'event/activity/addCustomer'
		];

		$events[] = [
			'code'    => 'activity_customer_edit',
			'trigger' => 'catalog/model/account/customer/editCustomer/after',
			'action'  => 'event/activity/editCustomer'
		];

		$events[] = [
			'code'    => 'activity_customer_password',
			'trigger' => 'catalog/model/account/customer/editPassword/after',
			'action'  => 'event/activity/editPassword'
		];

		$events[] = [
			'code'    => 'activity_customer_forgotten',
			'trigger' => 'catalog/model/account/customer/editCode/after',
			'action'  => 'event/activity/forgotten'
		];

		$events[] = [
			'code'    => 'activity_transaction',
			'trigger' => 'catalog/model/account/customer/addTransaction/after',
			'action'  => 'event/activity/addTransaction'
		];

		$events[] = [
			'code'    => 'activity_customer_login',
			'trigger' => 'catalog/model/account/customer/deleteLoginAttempts/after',
			'action'  => 'event/activity/login'
		];

		$events[] = [
			'code'    => 'activity_address_add',
			'trigger' => 'catalog/model/account/address/addAddress/after',
			'action'  => 'event/activity/addAddress'
		];

		$events[] = [
			'code'    => 'activity_address_edit',
			'trigger' => 'catalog/model/account/address/editAddress/after',
			'action'  => 'event/activity/editAddress'
		];

		$events[] = [
			'code'    => 'activity_address_delete',
			'trigger' => 'catalog/model/account/address/deleteAddress/after',
			'action'  => 'event/activity/deleteAddress'
		];

		$events[] = [
			'code'    => 'activity_affiliate_add',
			'trigger' => 'catalog/model/account/customer/addAffiliate/after',
			'action'  => 'event/activity/addAffiliate'
		];

		$events[] = [
			'code'    => 'activity_affiliate_edit',
			'trigger' => 'catalog/model/account/customer/editAffiliate/after',
			'action'  => 'event/activity/editAffiliate'
		];

		$events[] = [
			'code'    => 'activity_order_add',
			'trigger' => 'catalog/model/checkout/order/addHistory/before',
			'action'  => 'event/activity/addHistory'
		];

		$events[] = [
			'code'    => 'activity_return_add',
			'trigger' => 'catalog/model/account/returns/addReturn/after',
			'action'  => 'event/activity/addReturn'
		];

		$events[] = [
			'code'    => 'mail_transaction',
			'trigger' => 'catalog/model/account/customer/addTransaction/after',
			'action'  => 'mail/transaction'
		];

		$events[] = [
			'code'    => 'mail_forgotten',
			'trigger' => 'catalog/model/account/customer/editCode/after',
			'action'  => 'mail/forgotten'
		];

		$events[] = [
			'code'    => 'mail_customer_add',
			'trigger' => 'catalog/model/account/customer/addCustomer/after',
			'action'  => 'mail/register'
		];

		$events[] = [
			'code'    => 'mail_customer_alert',
			'trigger' => 'catalog/model/account/customer/addCustomer/after',
			'action'  => 'mail/register/alert'
		];

		$events[] = [
			'code'    => 'mail_affiliate_add',
			'trigger' => 'catalog/model/account/customer/addAffiliate/after',
			'action'  => 'mail/affiliate'
		];

		$events[] = [
			'code'    => 'mail_affiliate_alert',
			'trigger' => 'catalog/model/account/customer/addAffiliate/after',
			'action'  => 'mail/affiliate/alert'
		];

		$events[] = [
			'code'    => 'mail_voucher',
			'trigger' => 'catalog/model/checkout/order/addHistory/after',
			'action'  => 'extension/opencart/voucher/send'
		];

		$events[] = [
			'code'    => 'mail_order_add',
			'trigger' => 'catalog/model/checkout/order/addHistory/before',
			'action'  => 'mail/order'
		];

		$events[] = [
			'code'    => 'mail_order_alert',
			'trigger' => 'catalog/model/checkout/order/addHistory/before',
			'action'  => 'mail/order/alert'
		];

		$events[] = [
			'code'    => 'statistics_review_add',
			'trigger' => 'catalog/model/catalog/review/addReview/after',
			'action'  => 'event/statistics/addReview'
		];

		$events[] = [
			'code'    => 'statistics_return_add',
			'trigger' => 'catalog/model/account/returns/addReturn/after',
			'action'  => 'event/statistics/addReturn'
		];

		$events[] = [
			'code'    => 'statistics_order_history',
			'trigger' => 'catalog/model/checkout/order/addHistory/after',
			'action'  => 'event/statistics/addHistory'
		];

		$events[] = [
			'code'    => 'admin_mail_affiliate_approve',
			'trigger' => 'admin/model/customer/customer_approval/approveAffiliate/after',
			'action'  => 'mail/affiliate/approve'
		];

		$events[] = [
			'code'    => 'admin_mail_affiliate_deny',
			'trigger' => 'admin/model/customer/customer_approval/denyAffiliate/after',
			'action'  => 'mail/affiliate/deny'
		];

		$events[] = [
			'code'    => 'admin_mail_customer_approve',
			'trigger' => 'admin/model/customer/customer_approval/approveCustomer/after',
			'action'  => 'mail/customer/approve'
		];

		$events[] = [
			'code'    => 'admin_mail_customer_deny',
			'trigger' => 'admin/model/customer/customer_approval/denyCustomer/after',
			'action'  => 'mail/customer/deny'
		];

		$events[] = [
			'code'    => 'admin_mail_reward',
			'trigger' => 'admin/model/customer/customer/addReward/after',
			'action'  => 'mail/reward'
		];

		$events[] = [
			'code'    => 'admin_mail_transaction',
			'trigger' => 'admin/model/customer/customer/addTransaction/after',
			'action'  => 'mail/transaction'
		];

		$events[] = [
			'code'    => 'admin_mail_return',
			'trigger' => 'admin/model/sale/return/addReturn/after',
			'action'  => 'mail/return'
		];

		$events[] = [
			'code'    => 'admin_mail_forgotten',
			'trigger' => 'admin/model/user/user/editCode/after',
			'action'  => 'mail/forgotten'
		];

		$events[] = [
			'code'    => 'admin_currency_add',
			'trigger' => 'admin/model/currency/addCurrency/after',
			'action'  => 'event/currency'
		];

		$events[] = [
			'code'    => 'admin_currency_edit',
			'trigger' => 'admin/model/currency/editCurrency/after',
			'action'  => 'event/currency'
		];

		$events[] = [
			'code'    => 'admin_setting',
			'trigger' => 'admin/model/setting/setting/editSetting/after',
			'action'  => 'event/currency'
		];

		foreach ($events as $event) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($event['code']) . "'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($event['code']) . "', `trigger` = '" . $this->db->escape($event['trigger']) . "', `action` = '" . $this->db->escape($event['action']) . "', `status` = '1', `sort_order` = '0'");
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'admin/model/sale/return/addHistory/after' WHERE `code` = 'admin_mail_return'");

		// extension_install
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'extension_id'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD `extension_id` int NOT NULL AFTER `extension_install_id`");
		}

		// If backup storage directory does not exist
		if (!is_dir(DIR_STORAGE . 'backup')) {
			mkdir(DIR_STORAGE . 'backup', '0644');

			$handle = fopen(DIR_STORAGE . 'backup/index.html', 'w');

			fclose($handle);
		}

		// If marketplace storage directory does not exist
		if (!is_dir(DIR_STORAGE . 'marketplace')) {
			mkdir(DIR_STORAGE . 'marketplace', '0644');

			$handle = fopen(DIR_STORAGE . 'marketplace/index.html', 'w');

			fclose($handle);
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'payment_free_checkout_order_status_id' WHERE `key` = 'free_checkout_order_status_id'");

		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'config_pagination' WHERE `key` = 'config_limit_admin'");
	}
}