<?php
class ModelUpgrade1010 extends Model {
	public function upgrade() {
		// Add missing core events
		$events = array(
			array('code' => 'activity_customer_add', 'trigger' => 'catalog/model/account/customer/addCustomer/after', 'action' => 'event/activity/addCustomer'),
			array('code' => 'activity_customer_edit', 'trigger' => 'catalog/model/account/customer/editCustomer/after', 'action' => 'event/activity/editCustomer'),
			array('code' => 'activity_customer_password', 'trigger' => 'catalog/model/account/customer/editPassword/after', 'action' => 'event/activity/editPassword'),
			array('code' => 'activity_customer_forgotten', 'trigger' => 'catalog/model/account/customer/editCode/after', 'action' => 'event/activity/forgotten'),
			array('code' => 'activity_transaction', 'trigger' => 'catalog/model/account/customer/addTransaction/after', 'action' => 'event/activity/addTransaction'),
			array('code' => 'activity_customer_login', 'trigger' => 'catalog/model/account/customer/deleteLoginAttempts/after', 'action' => 'event/activity/login'),
			array('code' => 'activity_address_add', 'trigger' => 'catalog/model/account/address/addAddress/after', 'action' => 'event/activity/addAddress'),
			array('code' => 'activity_address_edit', 'trigger' => 'catalog/model/account/address/editAddress/after', 'action' => 'event/activity/editAddress'),
			array('code' => 'activity_address_delete', 'trigger' => 'catalog/model/account/address/deleteAddress/after', 'action' => 'event/activity/deleteAddress'),
			array('code' => 'activity_affiliate_add', 'trigger' => 'catalog/model/account/customer/addAffiliate/after', 'action' => 'event/activity/addAffiliate'),
			array('code' => 'activity_affiliate_edit', 'trigger' => 'catalog/model/account/customer/editAffiliate/after', 'action' => 'event/activity/editAffiliate'),
			array('code' => 'activity_order_add', 'trigger' => 'catalog/model/checkout/order/addOrderHistory/before', 'action' => 'event/activity/addOrderHistory'),
			array('code' => 'activity_return_add', 'trigger' => 'catalog/model/account/return/addReturn/after', 'action' => 'event/activity/addReturn'),
			array('code' => 'mail_transaction', 'trigger' => 'catalog/model/account/customer/addTransaction/after', 'action' => 'mail/transaction'),
			array('code' => 'mail_forgotten', 'trigger' => 'catalog/model/account/customer/editCode/after', 'action' => 'mail/forgotten'),
			array('code' => 'mail_customer_add', 'trigger' => 'catalog/model/account/customer/addCustomer/after', 'action' => 'mail/register'),
			array('code' => 'mail_customer_alert', 'trigger' => 'catalog/model/account/customer/addCustomer/after', 'action' => 'mail/register/alert'),
			array('code' => 'mail_affiliate_add', 'trigger' => 'catalog/model/account/customer/addAffiliate/after', 'action' => 'mail/affiliate'),
			array('code' => 'mail_affiliate_alert', 'trigger' => 'catalog/model/account/customer/addAffiliate/after', 'action' => 'mail/affiliate/alert'),
			array('code' => 'mail_voucher', 'trigger' => 'catalog/model/checkout/order/addOrderHistory/after', 'action' => 'extension/total/voucher/send'),
			array('code' => 'mail_order_add', 'trigger' => 'catalog/model/checkout/order/addOrderHistory/before', 'action' => 'mail/order'),
			array('code' => 'mail_order_alert', 'trigger' => 'catalog/model/checkout/order/addOrderHistory/before', 'action' => 'mail/order/alert'),
			array('code' => 'statistics_review_add', 'trigger' => 'catalog/model/catalog/review/addReview/after', 'action' => 'event/statistics/addReview'),
			array('code' => 'statistics_return_add', 'trigger' => 'catalog/model/account/return/addReturn/after', 'action' => 'event/statistics/addReturn'),
			array('code' => 'statistics_order_history', 'trigger' => 'catalog/model/checkout/order/addOrderHistory/after', 'action' => 'event/statistics/addOrderHistory'),
			array('code' => 'admin_mail_affiliate_approve', 'trigger' => 'admin/model/customer/customer_approval/approveAffiliate/after', 'action' => 'mail/affiliate/approve'),
			array('code' => 'admin_mail_affiliate_deny', 'trigger' => 'admin/model/customer/customer_approval/denyAffiliate/after', 'action' => 'mail/affiliate/deny'),
			array('code' => 'admin_mail_customer_approve', 'trigger' => 'admin/model/customer/customer_approval/approveCustomer/after', 'action' => 'mail/customer/approve'),
			array('code' => 'admin_mail_customer_deny', 'trigger' => 'admin/model/customer/customer_approval/denyCustomer/after', 'action' => 'mail/customer/deny'),
			array('code' => 'admin_mail_reward', 'trigger' => 'admin/model/customer/customer/addReward/after', 'action' => 'mail/reward'),
			array('code' => 'admin_mail_transaction', 'trigger' => 'admin/model/customer/customer/addTransaction/after', 'action' => 'mail/transaction'),
			array('code' => 'admin_mail_return', 'trigger' => 'admin/model/sale/return/addReturn/after', 'action' => 'mail/return'),
			array('code' => 'admin_mail_forgotten', 'trigger' => 'admin/model/user/user/editCode/after', 'action' => 'mail/forgotten')
		);

		$result = $this->db->query("SELECT GROUP_CONCAT(`code` SEPARATOR ', ') as 'events' FROM `" . DB_PREFIX . "event`");

		if ($result->num_rows) {
			$events_code = explode($result->row['events'], ', ');
		} else {
			$events_code = array();
		}

		foreach ($events as $event) {
			if (!in_array($event['code'], $events_code)) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($event['code']) . "', `trigger` = '" . $this->db->escape($event['trigger']) . "', `action` = '" . $this->db->escape($event['action']) . "', `status` = 1, `sort_order` = 0");
			}
		}

		return true;
	}
}