<?php
namespace Opencart\Catalog\Controller\Extension\OcPaymentExample\Cron;
class CreditCard extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/oc_payment_example/cron/credit_card');

		$error = [];

		if (isset($this->session->data['order_id'])) {
			$order_id = (int)$this->session->data['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$this->load->model('checkout/subscription');

			$subscription_info = $this->model_checkout_subscription->getSubscription($order_info['subscription_id']);

			if (!$subscription_info) {
				$error['subscription'] = $this->language->get('error_subscription');
			}

			// Get credit card ID
			$credit_card_id = substr(strstr($order_info['payment_method']['code'], '.'), 1);

			$this->load->model('extension/oc_payment_example/payment/credit_card');

			$credit_card_info = $this->model_extension_oc_payment_example_payment_credit_card->getCreditCard($order_info['customer_id'], $credit_card_id);

			if (!$credit_card_info) {
				$error['credit_card'] = $this->language->get('error_credit_card');
			}
		} else {
			$error['order'] = $this->language->get('error_order');
		}

		if (!$error) {
			// Credit Card charge code goes here
			$response = $this->config->get('payment_credit_card_response');

			// Add report to the credit card table
			$report_data = [
				'order_id'       => $order_id,
				'credit_card_id' => $credit_card_info['credit_card_id'],
				'card_number'    => $credit_card_info['card_number'],
				'type'           => $credit_card_info['type'],
				'amount'         => $order_info['total'],
				'response'       => $response
			];

			$this->model_extension_oc_payment_example_payment_credit_card->addReport($this->customer->getId(), $report_data);

			if ($response) {
				$order_status_id = (int)$this->config->get('payment_credit_card_approved_status_id');
			} else {
				$order_status_id = (int)$this->config->get('payment_credit_card_denied_status_id');
			}

			// If payment order status is active or processing
			if (in_array($order_status_id, (array)$this->config->get('config_processing_status') + (array)$this->config->get('config_complete_status'))) {
				$remaining = 0;
				$date_next = '';

				if ($subscription_info['trial_status'] && $subscription_info['trial_remaining'] > 1) {
					$remaining = $subscription_info['trial_remaining'] - 1;
					$date_next = date('Y-m-d', strtotime('+' . $subscription_info['trial_cycle'] . ' ' . $subscription_info['trial_frequency']));

					$this->model_checkout_subscription->editTrialRemaining($subscription_info['subscription_id'], $remaining);
				} elseif ($subscription_info['duration'] && $subscription_info['remaining']) {
					$remaining = $subscription_info['remaining'] - 1;
					$date_next = date('Y-m-d', strtotime('+' . $subscription_info['cycle'] . ' ' . $subscription_info['frequency']));

					// If duration make sure there is remaining
					$this->model_checkout_subscription->editRemaining($subscription_info['subscription_id'], $remaining);
				} elseif (!$subscription_info['duration']) {
					// If duration is unlimited
					$date_next = date('Y-m-d', strtotime('+' . $subscription_info['cycle'] . ' ' . $subscription_info['frequency']));
				}

				if ($date_next) {
					$this->model_checkout_subscription->editDateNext($order_info['subscription_id'], $date_next);
				}

				$this->model_checkout_subscription->addHistory($order_info['subscription_id'], $this->config->get('config_subscription_active_status_id'), $this->language->get('text_success'));
			} else {
				// If payment failed change subscription history to failed
				$this->model_checkout_subscription->addHistory($order_info['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('error_payment_failed'));
			}

			$this->model_checkout_order->addHistory($order_id, $order_status_id);
		} else {
			$this->model_checkout_order->addHistory($order_id, $this->config->get('config_failed_status_id'));

			// Add subscription history failed if payment method for cron didn't exist
			$this->model_checkout_subscription->addHistory($order_info['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('text_log'));

			// Log errors
			foreach ($error as $key => $value) {
				$this->model_checkout_subscription->addLog($order_info['subscription_id'], $key, $value);
			}
		}
	}
}
