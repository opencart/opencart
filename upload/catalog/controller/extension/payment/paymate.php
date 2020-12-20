<?php
class ControllerExtensionPaymentPaymate extends Controller {
	public function index() {
		if (!$this->config->get('payment_paymate_test')) {
			$data['action'] = 'https://www.paymate.com/PayMate/ExpressPayment';
		} else {
			$data['action'] = 'https://www.paymate.com.au/PayMate/TestExpressPayment';
		}

		$this->load->model('checkout/order');

		if(!isset($this->session->data['order_id'])) {
			return false;
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['mid'] = $this->config->get('payment_paymate_username');
		$data['amt'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$data['currency'] = $order_info['currency_code'];
		$data['ref'] = $order_info['order_id'];

		$data['pmt_sender_email'] = $order_info['email'];
		$data['pmt_contact_firstname'] = $order_info['payment_firstname'];
		$data['pmt_contact_surname'] = $order_info['payment_lastname'];
		$data['pmt_contact_phone'] = $order_info['telephone'];
		$data['pmt_country'] = $order_info['payment_iso_code_2'];

		$data['regindi_address1'] = $order_info['payment_address_1'];
		$data['regindi_address2'] = $order_info['payment_address_2'];
		$data['regindi_sub'] = $order_info['payment_city'];
		$data['regindi_state'] = $order_info['payment_zone'];
		$data['regindi_pcode'] = $order_info['payment_postcode'];

		$data['return'] = $this->url->link('extension/payment/paymate/callback', 'hash=' . md5($order_info['order_id'] . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . $order_info['currency_code'] . $this->config->get('payment_paymate_password')));

		return $this->load->view('extension/payment/paymate', $data);
	}

	public function callback() {
		$this->load->language('extension/payment/paymate');

		if (isset($this->request->post['ref'])) {
			$order_id = $this->request->post['ref'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$error = '';

			if (!isset($this->request->post['responseCode']) || !isset($this->request->get['hash'])) {
				$error = $this->language->get('text_unable');
			} elseif ($this->request->get['hash'] != md5($order_info['order_id'] . $this->currency->format($this->request->post['paymentAmount'], $this->request->post['currency'], 1.0000000, false) . $this->request->post['currency'] . $this->config->get('payment_paymate_password'))) {
				$error = $this->language->get('text_unable');
			} elseif ($this->request->post['responseCode'] != 'PA' && $this->request->post['responseCode'] != 'PP') {
				$error = $this->language->get('text_declined');
			}
		} else {
			$error = $this->language->get('text_unable');
		}

		if ($error) {
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_basket'),
				'href' => $this->url->link('checkout/cart')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_checkout'),
				'href' => $this->url->link('checkout/checkout', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_failed'),
				'href' => $this->url->link('checkout/success')
			);

			$data['text_message'] = sprintf($this->language->get('text_failed_message'), $error, $this->url->link('information/contact'));

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('common/success', $data));
		} else {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_paymate_order_status_id'));

			$this->response->redirect($this->url->link('checkout/success'));
		}
	}
}