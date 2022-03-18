<?php
namespace Opencart\Admin\Controller\Cron;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->language('sale/subscription');
		
		$this->load->model('setting/extension');

		$time = time();

		$filter_data = [
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'filter_date_next'              => date('Y-m-d H:i:s', $time)
		];

		$this->load->model('sale/subscription');
		$this->load->model('setting/extension');

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			if ($this->config->get('config_subscription_active_status_id') == $result['subscription_status_id']) {

				if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {
					$trial_price = $result['trial_price'];
				} elseif (!$result['duration'] || $result['remaining']) {
					$price = $result['price'];
				}

				$subscription_status_id = $this->config->get('config_subscription_status_id');

				// Get the payment method used by the subscription
				$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $result['payment_code']);

				// Check payment status
				if ($extension_info && $this->config->get('config_' . $result['payment_code'] . '_status')) {
					$this->load->model('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);

					if (property_exists($this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}, 'recurringPayments')) {

						$subscription_status_id = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->recurringPayment($result['customer_id'], $result['customer_payment_id'], $price);

					} else {
						// Failed if payment method does not have recurring payment method
						$subscription_status_id = $this->config->get('config_subscription_failed_status_id');
					}
				} else {
					$subscription_status_id = $this->config->get('config_subscription_failed_status_id');
				}

				// History
				if ($result['subscription_status_id'] != $subscription_status_id) {
					$comment = sprintf($this->language->get('error_payment_code'), $result['payment_code']);
					
					$this->model_sale_subscription->addHistory($result['subscription_id'], $subscription_status_id, $comment, true);
				}
				
				// Transaction
				if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
					$this->model_sale_subscription->addTransaction($result['subscription_id'], 'payment success', $result['amount'], $result['order_id']);
				}

				// Success
				if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {

					if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {
						if ($result['trial_duration'] && $result['trial_remaining']) {
							$this->model_sale_subscription->editTrialRemaining($result['subscription_id'], $result['trial_remaining'] - 1);
						}

						$this->model_sale_subscription->editTrialDateNext($result['subscription_id'], date('Y-m-d', strtotime('+' . $result['trial_cycle'] . ' ' . $result['trial_frequency'])));
					} elseif (!$result['duration'] || $result['remaining']) {
						if ($result['duration'] && $result['remaining']) {
							$this->model_sale_subscription->editRemaining($result['subscription_id'], $result['remaining'] - 1);
						}

						$this->model_sale_subscription->editDateNext($result['subscription_id'], strtotime('+' . $result['duration'] . ' ' . $result['frequency'], strtotime($result['date_next'])));
					}
				}
			}
		}
	}
}
