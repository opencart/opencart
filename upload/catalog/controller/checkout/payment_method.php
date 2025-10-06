<?php
namespace Opencart\Catalog\Controller\Checkout;
/**
 * Class PaymentMethod
 *
 * @package Opencart\Catalog\Controller\Checkout
 */
class PaymentMethod extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('checkout/payment_method');

		if (isset($this->session->data['payment_method'])) {
			$data['payment_method'] = $this->session->data['payment_method']['name'];
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['payment_method'] = '';
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = 0;
		}

		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_checkout_id'));

		if ($information_info) {
			$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information.info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_checkout_id')), $information_info['title']);
		} else {
			$data['text_agree'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('checkout/payment_method', $data);
	}

	/**
	 * Get Methods
	 *
	 * @return void
	 */
	public function getMethods(): void {
		$this->load->language('checkout/payment_method');

		$json = [];

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Validate if customer session data is set
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Validate shipping
			if ($this->cart->hasShipping()) {
				// Validate shipping address
				if (!isset($this->session->data['shipping_address']['address_id'])) {
					$json['error'] = $this->language->get('error_shipping_address');
				}

				// Validate shipping method
				if (!isset($this->session->data['shipping_method'])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			}
		}

		if (!$json) {
			$payment_address = [];

			if ($this->config->get('config_checkout_payment_address') && isset($this->session->data['payment_address'])) {
				$payment_address = $this->session->data['payment_address'];
			} elseif ($this->config->get('config_checkout_shipping_address') && isset($this->session->data['shipping_address']['address_id'])) {
				$payment_address = $this->session->data['shipping_address'];
			}

			// Payment Methods
			$this->load->model('checkout/payment_method');

			$payment_methods = $this->model_checkout_payment_method->getMethods($payment_address);

			if ($payment_methods) {
				$json['payment_methods'] = $this->session->data['payment_methods'] = $payment_methods;
			} else {
				$json['error'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('checkout/payment_method');

		$json = [];

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Validate payment address, if required
			if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Validate shipping
			if ($this->cart->hasShipping()) {
				// Validate shipping address
				if (!isset($this->session->data['shipping_address']['address_id'])) {
					$json['error'] = $this->language->get('error_shipping_address');
				}

				// Validate shipping method
				if (!isset($this->session->data['shipping_method'])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			}

			// Validate payment methods
			if (isset($this->request->post['payment_method']) && isset($this->session->data['payment_methods'])) {
				$payment = explode('.', $this->request->post['payment_method']);

				if (!isset($payment[0]) || !isset($payment[1]) || !isset($this->session->data['payment_methods'][$payment[0]]['option'][$payment[1]])) {
					$json['error'] = $this->language->get('error_payment_method');
				}
			} else {
				$json['error'] = $this->language->get('error_payment_method');
			}
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$payment[0]]['option'][$payment[1]];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Comment
	 *
	 * @return void
	 */
	public function comment(): void {
		$this->load->language('checkout/payment_method');
		$this->load->model('checkout/order');

		$json = [];

		if (isset($this->session->data['order_id'])) {
			$order_id = (int)$this->session->data['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->post['comment'])) {
			$comment = (string)$this->request->post['comment'];
		} else {
			$comment = '';
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->session->data['comment'] = $comment;

			$this->model_checkout_order->editComment($order_id, $comment);

			$json['success'] = $this->language->get('text_comment');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Agree
	 *
	 * @return void
	 */
	public function agree(): void {
		$this->load->language('checkout/payment_method');

		$json = [];

		if (isset($this->request->post['agree'])) {
			$this->session->data['agree'] = $this->request->post['agree'];
		} else {
			unset($this->session->data['agree']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
