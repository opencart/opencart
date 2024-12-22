<?php
namespace Opencart\Catalog\Controller\Extension\OcPaymentExample\Cron;
class CreditCard extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/oc_payment_example/cron/credit_card');

		$this->load->model('checkout/order');

		$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_credit_card_approved_status_id'), '', true);


		// Load payment method used by the subscription
		$this->load->model('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);

		$key = 'model_extension_' . $extension_info['extension'] . '_payment_' . $extension_info['code'];

		if (isset($store->{$key}->charge)) {




			// Process payment
			$response_info = $store->{$key}->charge($this->customer->getId(), $order_data);

			print_r($response_info);

			if (isset($response_info['order_status_id'])) {
				$order_status_id = $response_info['order_status_id'];
			} else {
				$order_status_id = 0;
			}

			//$store->model_checkout_order->addHistory($store->session->data['order_id'], $order_status_id, $message, false);
			$store->model_checkout_order->addHistory($store->session->data['order_id'], $order_status_id);

			// If payment order status is active or processing
			if (!in_array($order_status_id, (array)$this->config->get('config_processing_status') + (array)$this->config->get('config_complete_status'))) {
				$remaining = 0;
				$date_next = '';

				if ($result['trial_status'] && $result['trial_remaining'] > 1) {
					$remaining = $result['trial_remaining'] - 1;
					$date_next = date('Y-m-d', strtotime('+' . $result['trial_cycle'] . ' ' . $result['trial_frequency']));

					$this->model_account_subscription->editTrialRemaining($result['subscription_id'], $remaining);
				} elseif ($result['duration'] && $result['remaining']) {
					$remaining = $result['remaining'] - 1;
					$date_next = date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency']));

					// If duration make sure there is remaining
					$this->model_account_subscription->editRemaining($result['subscription_id'], $remaining);
				} elseif (!$result['duration']) {
					// If duration is unlimited
					$date_next = date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency']));
				}

				if ($date_next) {
					$store->load->model('checkout/subscription');

					$store->model_checkout_subscription->editDateNext($result['subscription_id'], $date_next);
				}

				$store->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_active_status_id'), $store->language->get('text_success'));

			} else {

				// If payment failed change subscription history to failed
				$store->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $message);
			}

		} else {

			// Add subscription history failed if no charge method
			$store->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('error_payment_method'));
		}










	}
}
