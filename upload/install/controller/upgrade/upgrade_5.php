<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade4
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade5 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Adds missing events
		try {
			// Add missing default events
			$events = [];

			$events[] = [
				'code'    => 'activity_customer_add',
				'trigger' => 'catalog/model/account/customer.addCustomer/after',
				'action'  => 'event/activity.addCustomer'
			];

			$events[] = [
				'code'    => 'activity_customer_edit',
				'trigger' => 'catalog/model/account/customer.editCustomer/after',
				'action'  => 'event/activity.editCustomer'
			];

			$events[] = [
				'code'    => 'activity_customer_password',
				'trigger' => 'catalog/model/account/customer.editPassword/after',
				'action'  => 'event/activity.editPassword'
			];

			$events[] = [
				'code'    => 'activity_customer_forgotten',
				'trigger' => 'catalog/model/account/customer.addToken/after',
				'action'  => 'event/activity.forgotten'
			];

			$events[] = [
				'code'    => 'activity_transaction',
				'trigger' => 'catalog/model/account/customer.addTransaction/after',
				'action'  => 'event/activity.addTransaction'
			];

			$events[] = [
				'code'    => 'activity_customer_login',
				'trigger' => 'catalog/model/account/customer.deleteLoginAttempts/after',
				'action'  => 'event/activity.login'
			];

			$events[] = [
				'code'    => 'activity_address_add',
				'trigger' => 'catalog/model/account/address.addAddress/after',
				'action'  => 'event/activity.addAddress'
			];

			$events[] = [
				'code'    => 'activity_address_edit',
				'trigger' => 'catalog/model/account/address.editAddress/after',
				'action'  => 'event/activity.editAddress'
			];

			$events[] = [
				'code'    => 'activity_address_delete',
				'trigger' => 'catalog/model/account/address.deleteAddress/after',
				'action'  => 'event/activity.deleteAddress'
			];

			$events[] = [
				'code'    => 'activity_affiliate_add',
				'trigger' => 'catalog/model/account/customer.addAffiliate/after',
				'action'  => 'event/activity.addAffiliate'
			];

			$events[] = [
				'code'    => 'activity_affiliate_edit',
				'trigger' => 'catalog/model/account/customer.editAffiliate/after',
				'action'  => 'event/activity.editAffiliate'
			];

			$events[] = [
				'code'    => 'activity_order_add',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'event/activity.addHistory'
			];

			$events[] = [
				'code'    => 'activity_return_add',
				'trigger' => 'catalog/model/account/returns.addReturn/after',
				'action'  => 'event/activity.addReturn'
			];

			$events[] = [
				'code'    => 'mail_transaction',
				'trigger' => 'catalog/model/account/customer.addTransaction/after',
				'action'  => 'mail/transaction'
			];

			$events[] = [
				'code'    => 'mail_forgotten',
				'trigger' => 'catalog/model/account/customer.addToken/after',
				'action'  => 'mail/forgotten'
			];

			$events[] = [
				'code'    => 'mail_customer_add',
				'trigger' => 'catalog/model/account/customer.addCustomer/after',
				'action'  => 'mail/register'
			];

			$events[] = [
				'code'    => 'mail_customer_alert',
				'trigger' => 'catalog/model/account/customer.addCustomer/after',
				'action'  => 'mail/register.alert'
			];

			$events[] = [
				'code'    => 'mail_affiliate_add',
				'trigger' => 'catalog/model/account/customer.addAffiliate/after',
				'action'  => 'mail/affiliate'
			];

			$events[] = [
				'code'    => 'mail_affiliate_alert',
				'trigger' => 'catalog/model/account/customer.addAffiliate/after',
				'action'  => 'mail/affiliate.alert'
			];

			$events[] = [
				'code'    => 'mail_order_add',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'mail/order'
			];

			$events[] = [
				'code'    => 'mail_order_alert',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'mail/order.alert'
			];

			$events[] = [
				'code'    => 'statistics_review_add',
				'trigger' => 'catalog/model/catalog/review.addReview/after',
				'action'  => 'event/statistics.addReview'
			];

			$events[] = [
				'code'    => 'statistics_return_add',
				'trigger' => 'catalog/model/account/returns.addReturn/after',
				'action'  => 'event/statistics.addReturn'
			];

			$events[] = [
				'code'    => 'statistics_order_history',
				'trigger' => 'catalog/model/checkout/order.addHistory/after',
				'action'  => 'event/statistics.addHistory'
			];

			$events[] = [
				'code'    => 'admin_mail_affiliate_approve',
				'trigger' => 'admin/model/customer/customer_approval.approveAffiliate/after',
				'action'  => 'mail/affiliate.approve'
			];

			$events[] = [
				'code'    => 'admin_mail_affiliate_deny',
				'trigger' => 'admin/model/customer/customer_approval.denyAffiliate/after',
				'action'  => 'mail/affiliate.deny'
			];

			$events[] = [
				'code'    => 'admin_mail_customer_approve',
				'trigger' => 'admin/model/customer/customer_approval.approveCustomer/after',
				'action'  => 'mail/customer.approve'
			];

			$events[] = [
				'code'    => 'admin_mail_customer_deny',
				'trigger' => 'admin/model/customer/customer_approval.denyCustomer/after',
				'action'  => 'mail/customer.deny'
			];

			$events[] = [
				'code'    => 'admin_mail_reward',
				'trigger' => 'admin/model/customer/customer.addReward/after',
				'action'  => 'mail/reward'
			];

			$events[] = [
				'code'    => 'admin_mail_transaction',
				'trigger' => 'admin/model/customer/customer.addTransaction/after',
				'action'  => 'mail/transaction'
			];

			$events[] = [
				'code'    => 'admin_mail_return',
				'trigger' => 'admin/model/sale/return.addReturn/after',
				'action'  => 'mail/returns'
			];

			$events[] = [
				'code'    => 'admin_mail_forgotten',
				'trigger' => 'admin/model/user/user.addToken/after',
				'action'  => 'mail/forgotten'
			];

			$events[] = [
				'code'    => 'admin_currency_add',
				'trigger' => 'admin/model/currency.addCurrency/after',
				'action'  => 'event/currency'
			];

			$events[] = [
				'code'    => 'admin_currency_edit',
				'trigger' => 'admin/model/currency.editCurrency/after',
				'action'  => 'event/currency'
			];

			$events[] = [
				'code'    => 'admin_setting',
				'trigger' => 'admin/model/setting/setting.editSetting/after',
				'action'  => 'event/currency'
			];

			foreach ($events as $event) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($event['code']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($event['code']) . "', `trigger` = '" . $this->db->escape($event['trigger']) . "', `action` = '" . $this->db->escape($event['action']) . "', `status` = '1', `sort_order` = '0'");
				}
			}

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event`");

			foreach ($query->rows as $result) {
				if (!str_contains($result['trigger'], '.')) {
					$parts = explode('/', $result['trigger']);

					$string_1 = implode('/', array_slice($parts, 0, -2));
					$string_2 = implode('/', array_slice($parts, -2));

					$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = '" . $this->db->escape($string_1 . '.' . $string_2) . "' WHERE `event_id` = '" . (int)$result['event_id'] . "'");
				}
			}

			// Alter events table
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "event' AND COLUMN_NAME = 'date_added'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "event` DROP COLUMN `date_added`");
			}

			// Update current keys
			$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = 'admin/model/sale/returns.addHistory/after' WHERE `code` = 'admin_mail_return'");

			// Event - Remove admin promotion from OC 3.x, since it is no longer required to have in OC v4.x releases.
			$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `action` = 'extension/extension/promotion.getList'");

			// Rename subscription to mail_subscription
			$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `code` = 'mail_subscription' WHERE `code` = 'subscription'");

			// Fix https://github.com/opencart/opencart/issues/11594
			$this->db->query("UPDATE `" . DB_PREFIX . "layout_route` SET `route` = REPLACE(`route`, '|', '.')");
			$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `value` = REPLACE(`value`, '|', '.') WHERE `key` = 'route'");
			$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = REPLACE(`trigger`, '|', '.'), `action` = REPLACE(`action`, '|', '.')");
			$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `link` = REPLACE(`link`, '|', '.')");
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 5, 5, 11);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_6', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
