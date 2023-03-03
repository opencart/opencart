<?php
namespace Opencart\Catalog\Controller\Cron;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
        $this->load->language('cron/subscription');


		// Get all
        $this->load->model('checkout/order');
		$this->load->model('setting/store');

        $results = $this->model_checkout_order->getSubscriptionsByDateNext(date('Y-m-d H:i:s'));

		foreach ($results as $result) {
			$order_info = $this->model_checkout_order->getOrder($result['order_id']);

			if ($order_info && in_array($order_info['order_status_id'], (array)$this->config->get('config_complete_status'))) {
				$this->load->model('setting/store');

				$store = $this->model_setting_store->createStoreInstance($order_info['store_id'], $order_info['language_code']);




				// Order Details
				$order_data = $order_info;

				$order_data['products'] = [];

				$products = $this->model_checkout_order->getProduct($result['order_id'], $result['order_product_id']);

				$order_data['options'] = $this->model_checkout_order->getOptions($result['order_id'], $result['order_product_id']);
				$order_data['subscription'] = $this->model_checkout_order->getSubscription($result['order_id'], $result['order_product_id']);
				$order_data['voucher'] = $this->model_checkout_order->getVouchers($result['order_id'], $result['order_product_id']);
				$order_data['totals'] = $this->model_checkout_order->getTotals($result['order_id'], $result['order_product_id']);


				if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining']) && $result['subscription_status_id'] == $this->config->get('config_subscription_active_status_id')) {
					$amount = $result['trial_price'];
				} elseif (!$result['duration'] || $result['remaining']) {
					$amount = $result['price'];
				}

				$subscription_status_id = $this->config->get('config_subscription_status_id');

				// Get the payment method used by the subscription
				// Check payment status
				//$this->load->model('extension/payment/' . $payment_info['code']);


				// Transaction
				if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
					if ($result['trial_duration'] && $result['trial_remaining']) {
						$date_next = date('Y-m-d', strtotime('+' . $result['trial_cycle'] . ' ' . $result['trial_frequency']));
					} elseif ($result['duration'] && $result['remaining']) {
						$date_next = date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency']));
					}

					$filter_data = [
						'filter_date_next'              => $date_next,
						'filter_subscription_status_id' => $subscription_status_id,
						'start'                         => 0,
						'limit'                         => 1
					];

					$subscriptions = $this->model_account_subscription->getSubscriptions($filter_data);

					if ($subscriptions) {
						// Only match the latest order ID of the same customer ID
						// since new subscriptions cannot be re-added with the same
						// order ID; only as a new order ID added by an extension
						foreach ($subscriptions as $subscription) {
							if ($subscription['customer_id'] == $result['customer_id'] && ($subscription['subscription_id'] != $result['subscription_id']) && ($subscription['order_id'] != $result['order_id']) && ($subscription['order_product_id'] != $result['order_product_id'])) {
								$subscription_info = $this->model_account_subscription->getSubscription($subscription['subscription_id']);

								if ($subscription_info) {
								   // $this->model_account_subscription->addTransaction($subscription['subscription_id'], $subscription['order_id'], $this->language->get('text_success'), $amount, $subscription_info['type'], $subscription_info['payment_method'], $subscription_info['payment_code']);
								}
							}
						}
					}
				}

					// Failed if payment method does not have recurring payment method
					$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

					$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, $this->language->get('error_recurring'), true);


					$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

					$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, $this->language->get('error_extension'), true);




			$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

			$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, sprintf($this->language->get('error_payment'), ''), true);


		// History
		if ($result['subscription_status_id'] != $subscription_status_id) {
			$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, 'payment extension ' . $result['payment_code'] . ' could not be loaded', true);
		}

		// Success
		if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
			// Trial
			if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {
				if ($result['trial_duration'] && $result['trial_remaining']) {
					$this->model_account_subscription->editTrialRemaining($result['subscription_id'], $result['trial_remaining'] - 1);
				}
			} elseif (!$result['duration'] || $result['remaining']) {
				// Subscription
				if ($result['duration'] && $result['remaining']) {
					$this->model_account_subscription->editRemaining($result['subscription_id'], $result['remaining'] - 1);
				}
			}
		}




    }
}
