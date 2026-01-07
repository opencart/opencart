<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade8
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade8 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Rename events
			$replace = [];

			$replace[] = [
				'code_old' => 'subscription',
				'code_new' => 'mail_subscription'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_approve',
				'code_new' => 'mail_admin_customer_approve'
			];

			$replace[] = [
				'code_old' => 'admin_mail_affiliate_deny',
				'code_new' => 'mail_admin_affiliate_deny'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_approve',
				'code_new' => 'mail_admin_customer_approve'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_deny',
				'code_new' => 'mail_admin_customer_deny'
			];

			$replace[] = [
				'code_old' => 'admin_mail_customer_transaction',
				'code_new' => 'mail_admin_customer_transaction'
			];

			$replace[] = [
				'code_old' => 'admin_mail_forgotten',
				'code_new' => 'mail_admin_forgotten'
			];

			$replace[] = [
				'code_old' => 'admin_mail_gdpr',
				'code_new' => 'mail_admin_gdpr'
			];

			$replace[] = [
				'code_old' => 'admin_mail_return',
				'code_new' => 'mail_admin_return'
			];

			$replace[] = [
				'code_old' => 'admin_mail_reward',
				'code_new' => 'mail_admin_reward'
			];

			$replace[] = [
				'code_old' => 'admin_mail_transaction',
				'code_new' => 'mail_admin_transaction'
			];

			$replace[] = [
				'code_old' => 'admin_mail_user_authorize',
				'code_new' => 'mail_admin_user_authorize'
			];

			$replace[] = [
				'code_old' => 'admin_mail_user_authorize_reset',
				'code_new' => 'mail_admin_user_authorize_reset'
			];

			$replace[] = [
				'code_old' => 'admin_mail_user_forgotten',
				'code_new' => 'mail_admin_user_forgotten'
			];

			$replace[] = [
				'code_old' => 'admin_currency_add',
				'code_new' => 'currency_add'
			];

			$replace[] = [
				'code_old' => 'admin_currency_edit',
				'code_new' => 'currency_edit'
			];

			$replace[] = [
				'code_old' => 'admin_currency_setting',
				'code_new' => 'currency_setting'
			];

			foreach ($replace as $result) {
				$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($result['code_new']) . "' WHERE `code` = '" . $this->db->escape($result['code_old']) . "'");
			}

			// Add missing default events
			$events = [];

			// Activity
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
				'code'    => 'activity_customer_transaction',
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
				'code'    => 'currency_setting',
				'trigger' => 'admin/model/setting/setting.editSetting/after',
				'action'  => 'event/currency'
			];

			// Mail
			$events[] = [
				'code'    => 'mail_customer_transaction',
				'trigger' => 'catalog/model/account/customer.addTransaction/after',
				'action'  => 'mail/transaction'
			];

			$events[] = [
				'code'    => 'mail_customer_forgotten',
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
				'code'    => 'mail_order',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'mail/order'
			];

			$events[] = [
				'code'    => 'mail_order_alert',
				'trigger' => 'catalog/model/checkout/order.addHistory/before',
				'action'  => 'mail/order.alert'
			];

			$events[] = [
				'code'    => 'mail_admin_affiliate_approve',
				'trigger' => 'admin/model/customer/customer_approval.approveAffiliate/after',
				'action'  => 'mail/affiliate.approve'
			];

			$events[] = [
				'code'    => 'mail_admin_affiliate_deny',
				'trigger' => 'admin/model/customer/customer_approval.denyAffiliate/after',
				'action'  => 'mail/affiliate.deny'
			];

			$events[] = [
				'code'    => 'mail_admin_customer_approve',
				'trigger' => 'admin/model/customer/customer_approval.approveCustomer/after',
				'action'  => 'mail/customer.approve'
			];

			$events[] = [
				'code'    => 'mail_admin_customer_deny',
				'trigger' => 'admin/model/customer/customer_approval.denyCustomer/after',
				'action'  => 'mail/customer.deny'
			];

			$events[] = [
				'code'    => 'mail_admin_reward',
				'trigger' => 'admin/model/customer/customer.addReward/after',
				'action'  => 'mail/reward'
			];

			$events[] = [
				'code'    => 'mail_admin_customer_transaction',
				'trigger' => 'admin/model/customer/customer.addTransaction/after',
				'action'  => 'mail/transaction'
			];

			$events[] = [
				'code'    => 'mail_admin_return',
				'trigger' => 'admin/model/sale/return.addReturn/after',
				'action'  => 'mail/returns'
			];

			$events[] = [
				'code'    => 'mail_admin_forgotten',
				'trigger' => 'admin/model/user/user.addToken/after',
				'action'  => 'mail/forgotten'
			];

			// Statistics
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

			foreach ($events as $event) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($event['code']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($event['code']) . "', `trigger` = '" . $this->db->escape($event['trigger']) . "', `action` = '" . $this->db->escape($event['action']) . "', `status` = '1', `sort_order` = '0'");
				}
			}

			$this->load->model('upgrade/upgrade');

			$events = $this->model_upgrade_upgrade->getRecords('event');

			foreach ($events as $event) {
				if (!str_contains($event['trigger'], '.')) {
					$parts = explode('/', $event['trigger']);

					$string_1 = implode('/', array_slice($parts, 0, -2));
					$string_2 = implode('/', array_slice($parts, -2));

					$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = '" . $this->db->escape($string_1 . '.' . $string_2) . "' WHERE `event_id` = '" . (int)$event['event_id'] . "'");
				}
			}

			// Alter events table
			if ($this->model_upgrade_upgrade->hasField('event', 'date_added')) {
				$this->model_upgrade_upgrade->dropField('event', 'date_added');
			}

			// Event - Remove admin promotion from OC 3.x, since it is no longer required to have in OC v4.x releases.
			$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `action` = 'extension/extension/promotion.getList'");
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 8, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_9', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
