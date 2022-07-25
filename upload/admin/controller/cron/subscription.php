<?php
namespace Opencart\Admin\Controller\Cron;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->language('cron/subscription');

		$filter_data = [
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'filter_date_next'              => date('Y-m-d H:i:s')
		];

		$this->load->model('sale/subscription');
		$this->load->model('customer/customer');
		$this->load->model('setting/extension');

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			if ($this->config->get('config_subscription_active_status_id') == $result['subscription_status_id']) {
				if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {
					$amount = $result['trial_price'];
				} elseif (!$result['duration'] || $result['remaining']) {
					$amount = $result['price'];
				}

				$subscription_status_id = $this->config->get('config_subscription_status_id');

				// Get the payment method used by the subscription
				$payment_info = $this->model_customer_customer->getPaymentMethod($result['customer_id'], $result['customer_payment_id']);

				if ($payment_info) {
					// Check payment status
					if ($this->config->get('payment_' . $payment_info['code'] . '_status')) {
						$this->load->model('extension/' . $payment_info['extension'] . '/payment/' . $payment_info['code']);

						if (property_exists($this->{'model_extension_' . $payment_info['extension'] . '_payment_' . $payment_info['code']}, 'charge') ) {
							$subscription_status_id = $this->{'model_extension_' . $payment_info['extension'] . '_payment_' . $payment_info['code']}->charge($result['customer_id'], $result['customer_payment_id'], $amount);

							// Transaction
							if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
								$this->model_sale_subscription->addTransaction($result['subscription_id'], $this->language->get('text_success'), $amount, $result['order_id']);
							}
						} else {
							// Failed if payment method does not have recurring payment method
							$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

							$this->model_sale_subscription->addHistory($result['subscription_id'], $subscription_status_id, $this->language->get('error_recurring'), true);
						}
					} else {
						$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

						$this->model_sale_subscription->addHistory($result['subscription_id'], $subscription_status_id, $this->language->get('error_extension'), true);
					}
				} else {
					$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

					$this->model_sale_subscription->addHistory($result['subscription_id'], $subscription_status_id, sprintf($this->language->get('error_payment'), ''), true);
				}

				// History
				if ($result['subscription_status_id'] != $subscription_status_id) {
					$this->model_sale_subscription->addHistory($result['subscription_id'], $subscription_status_id, 'payment extension ' . $result['payment_code'] . ' could not be loaded', true);
				}
				
				// Success
				if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
					// Trial
					if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {

						if ($result['trial_duration'] && $result['trial_remaining']) {
							$this->model_sale_subscription->editTrialRemaining($result['subscription_id'], $result['trial_remaining'] - 1);
						}

						//$this->model_sale_subscription->editDateNext($result['subscription_id'], date('Y-m-d', strtotime('+' . $result['trial_cycle'] . ' ' . $result['trial_frequency'])));
					} elseif (!$result['duration'] || $result['remaining']) {
						// Subscription
						if ($result['duration'] && $result['remaining']) {
							$this->model_sale_subscription->editRemaining($result['subscription_id'], $result['remaining'] - 1);
						}

						//$this->model_sale_subscription->editDateNext($result['subscription_id'], date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency'])));
					}
				}
			}
		}
	}
}
