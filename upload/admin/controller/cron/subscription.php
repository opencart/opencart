<?php

namespace Opencart\Admin\Controller\Cron;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->model('setting/extension');

		$time = time();

		$filter_data = [
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'filter_date_added' => date('Y-m-d H:i:s', $time)
		];

		$this->load->model('sale/subscription');
		$this->load->model('setting/extension');

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			if ($this->config->get('config_subscription_active_status_id') == $result['subscription_status_id']) {
				// 1. Get the payment method used by the subscription
				$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $result['payment_code']);

				// 2. Check payment status
				if ($extension_info && $this->config->get('config_' . $result['payment_code'] . '_status')) {
					$this->load->model('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);

					if (property_exists($this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}, 'recurringPayments')) {

						$subscription_status_id = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->recurringPayment($result['customer_id'], $result['payment_method_id'], $result['amount']);

						if ($subscription_status_id == $this->config->get('config_subscription_active_status_id')) {
							// 3. If successful add the transaction history
							$this->model_sale_subscription->addTransaction($result['subscription_id'], '', $result['amount'], $result['order_id']);
						}

					} else {
						// 4. Failed if payment method not found or enabled
						$subscription_status_id = $this->config->get('config_subscription_failed_status_id');
					}


				} else {
					// 4. Failed if payment method not found or enabled
					$subscription_status_id = $this->config->get('config_subscription_failed_status_id');
				}

				// 5. Add history of the subscription change
				$this->model_sale_subscription->addHistory($result['subscription_id'], $subscription_status_id, '', $notify);




				$this->model_sale_subscription->editNextPayment($result['subscription_id'], $subscription_status_id, '');



				$time = strtotime('+' . $result['trial_duration'] . ' ' . $result['trial_frequency'], strtotime($result['payment_date']));

				echo $time . "\n";


				//< ($time + 10
				//strtotime('+' . $result['trial_duration'] . ' ' . $result['trial_frequency'], strtotime($result['date_modified']));

				// Pending
				// Active
				// Expired
				// Canceled
				// Failed


				//if ($time > ) {


				//}

				// Payment



				if ($result['trial_status']) {
					$trial_price = $result['trial_price'];

					$time = match ($result['trial_frequency']) {
						'day'  => 'grey',
						'week' => 'green',
						'' => 'red',
					};


					if ($time > $result['date_modified']) {

					}

					$data['trial_frequency'] = $result['trial_frequency'];

					$data['trial_duration'] = $result['trial_duration'];
					$data['trial_cycle'] = $result['trial_cycle'];
					$data['trial_status'] = $result['trial_status'];
				}

				//$data['price'] = $subscription_info['price'];
				$data['frequency'] = $result['frequency'];
				$data['duration'] = $result['duration'];
				$data['cycle'] = $result['cycle'];
				//if () {
				//}
				//$result
			}
		}
	}
}