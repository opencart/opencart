<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Total;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Total
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->config->get('total_subscription_status')) {
			$this->load->language('extension/opencart/total/subscription');

			$data['save'] = $this->url->link('extension/opencart/total/subscription.save', 'language=' . $this->config->get('config_language'), true);
			$data['list'] = $this->url->link('checkout/cart.list', 'language=' . $this->config->get('config_language'), true);

			if (isset($this->session->data['subscription_discount'])) {
				$data['subscription_discount'] = $this->session->data['subscription_discount'];
			} else {
				$data['subscription_discount'] = '';
			}

			return $this->load->view('extension/opencart/total/subscription', $data);
		}

		return '';
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/total/subscription');

		$json = [];

		if (isset($this->request->post['subscription_discount'])) {
			$subscription_discount = $this->request->post['subscription_discount'];
		} else {
			$subscription_discount = '';
		}

		if (!$this->config->get('total_subscription_status')) {
			$json['error'] = $this->language->get('error_status');
		}

		if ($subscription) {
			$subscription_discount_info = $this->model_extension_opencart_total_subscription->getDiscount($subscription_discount);

			if (!$subscription_discount_info) {
				$json['error'] = $this->language->get('error_subscription_discount');
			}
		}

		if (!$json) {
			if ($subscription_discount_info) {
				$json['success'] = $this->language->get('text_success');

				$this->session->data['subscription_discount'] = $subscription_discount;
			} else {
				$json['success'] = $this->language->get('text_remove');

				unset($this->session->data['subscription_discount']);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
