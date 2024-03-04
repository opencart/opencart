<?php
class ControllerExtensionPaymentBluePayHosted extends Controller {
	public function index() {
		$this->load->language('extension/payment/bluepay_hosted');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/bluepay_hosted');

		if(!isset($this->session->data['order_id'])) {
			return false;
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['ORDER_ID'] = $this->session->data['order_id'];
		$data['NAME1'] = $order_info['payment_firstname'];
		$data['NAME2'] = $order_info['payment_lastname'];
		$data['ADDR1'] = $order_info['payment_address_1'];
		$data['ADDR2'] = $order_info['payment_address_2'];
		$data['CITY'] = $order_info['payment_city'];
		$data['STATE'] = $order_info['payment_zone'];
		$data['ZIPCODE'] = $order_info['payment_postcode'];
		$data['COUNTRY'] = $order_info['payment_country'];
		$data['PHONE'] = $order_info['telephone'];
		$data['EMAIL'] = $order_info['email'];

		$data['SHPF_FORM_ID'] = 'opencart01';
		$data['DBA'] = $this->config->get('payment_bluepay_hosted_account_name');
		$data['MERCHANT'] = $this->config->get('payment_bluepay_hosted_account_id');
		$data['SHPF_ACCOUNT_ID'] = $this->config->get('payment_bluepay_hosted_account_id');
		$data["TRANSACTION_TYPE"] = $this->config->get('payment_bluepay_hosted_transaction');
		$data["MODE"] = strtoupper($this->config->get('payment_bluepay_hosted_test'));

		$data['CARD_TYPES'] = 'vi-mc';

		if ($this->config->get('payment_bluepay_hosted_discover') == 1) {
			$data['CARD_TYPES'] .= '-di';
		}

		if ($this->config->get('payment_bluepay_hosted_amex') == 1) {
			$data['CARD_TYPES'] .= '-am';
		}

		$data["AMOUNT"] = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		$data['APPROVED_URL'] = $this->url->link('extension/payment/bluepay_hosted/callback', '', true);
		$data['DECLINED_URL'] = $this->url->link('extension/payment/bluepay_hosted/callback', '', true);
		$data['MISSING_URL'] = $this->url->link('extension/payment/bluepay_hosted/callback', '', true);
		$data['REDIRECT_URL'] = $this->url->link('extension/payment/bluepay_hosted/callback', '', true);

		$data['TPS_DEF'] = "MERCHANT APPROVED_URL DECLINED_URL MISSING_URL MODE TRANSACTION_TYPE TPS_DEF AMOUNT";
		$data['TAMPER_PROOF_SEAL'] = md5($this->config->get('payment_bluepay_hosted_secret_key') . $data['MERCHANT'] . $data['APPROVED_URL'] . $data['DECLINED_URL'] . $data['MISSING_URL'] . $data['MODE'] . $data['TRANSACTION_TYPE'] . $data['TPS_DEF'] . $data['AMOUNT']);

		$data['SHPF_TPS_DEF'] = "SHPF_FORM_ID SHPF_ACCOUNT_ID DBA TAMPER_PROOF_SEAL CARD_TYPES TPS_DEF SHPF_TPS_DEF AMOUNT";
		$data['SHPF_TPS'] = md5($this->config->get('payment_bluepay_hosted_secret_key') . $data['SHPF_FORM_ID'] . $data['SHPF_ACCOUNT_ID'] . $data['DBA'] . $data['TAMPER_PROOF_SEAL'] . $data['CARD_TYPES'] . $data['TPS_DEF'] . $data['SHPF_TPS_DEF'] . $data['AMOUNT']);

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');

		return $this->load->view('extension/payment/bluepay_hosted', $data);
	}

	public function callback() {
		$this->load->language('extension/payment/bluepay_hosted');

		$this->load->model('checkout/order');

		$this->load->model('extension/payment/bluepay_hosted');

		$response_data = $this->request->get;

		if (isset($this->session->data['order_id']) && $this->config->get('payment_bluepay_hosted_status')) {
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($response_data['Result'] == 'APPROVED') {
				$bluepay_hosted_order_id = $this->model_extension_payment_bluepay_hosted->addOrder($order_info, $response_data);

				if ($this->config->get('payment_bluepay_hosted_transaction') == 'SALE') {
					$this->model_extension_payment_bluepay_hosted->addTransaction($bluepay_hosted_order_id, 'payment', $order_info);
				} else {
					$this->model_extension_payment_bluepay_hosted->addTransaction($bluepay_hosted_order_id, 'auth', $order_info);
				}

				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_bluepay_hosted_order_status_id'));

				$this->response->redirect($this->url->link('checkout/success', '', true));
			} else {
				$this->session->data['error'] = $response_data['Result'] . ' : ' . $response_data['MESSAGE'];

				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}
		} else {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
	}

	public function adminCallback() {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->request->get));
	}
}
