<?php
class ControllerPaymentSagepayServer extends Controller {
	public function index() {
		$this->load->language('payment/sagepay_server');
		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_card_name'] = $this->language->get('text_card_name');
		$data['text_card_type'] = $this->language->get('text_card_type');
		$data['text_card_digits'] = $this->language->get('text_card_digits');
		$data['text_card_expiry'] = $this->language->get('text_card_expiry');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_card_existing'] = $this->language->get('entry_card_existing');
		$data['entry_card_new'] = $this->language->get('entry_card_new');
		$data['entry_card_save'] = $this->language->get('entry_card_save');
		$data['entry_cc_choice'] = $this->language->get('entry_cc_choice');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['action'] = $this->url->link('payment/sagepay_server/send', '', 'SSL');

		if ($this->config->get('sagepay_server_card') == '1') {
			$data['sagepay_server_card'] = true;
		} else {
			$data['sagepay_server_card'] = false;
		}

		$data['cards'] = array();

		if ($this->customer->isLogged() && $data['sagepay_server_card']) {
			$this->load->model('payment/sagepay_server');

			$data['cards'] = $this->model_payment_sagepay_server->getCards($this->customer->getId());
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sagepay_server.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/sagepay_server.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/sagepay_server.tpl', $data);
		}
	}

	public function send() {

		$payment_data = array();

		if ($this->config->get('sagepay_server_test') == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/vspserver-register.vsp';

			$payment_data['VPSProtocol'] = '3.00';
		} elseif ($this->config->get('sagepay_server_test') == 'test') {
			$url = 'https://test.sagepay.com/gateway/service/vspserver-register.vsp';

			$payment_data['VPSProtocol'] = '3.00';
		} elseif ($this->config->get('sagepay_server_test') == 'sim') {
			$url = 'https://test.sagepay.com/Simulator/VSPServerGateway.asp?Service=VendorRegisterTx';

			$payment_data['VPSProtocol'] = '2.23';
		}

		$this->load->model('checkout/order');
		$this->load->model('payment/sagepay_server');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$payment_data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$payment_data['Vendor'] = $this->config->get('sagepay_server_vendor');
		$payment_data['VendorTxCode'] = $this->session->data['order_id'] . 'T' . strftime("%Y%m%d%H%M%S") . mt_rand(1, 999);
		$payment_data['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		$payment_data['Currency'] = $this->currency->getCode();
		$payment_data['Description'] = substr($this->config->get('config_name'), 0, 100);
		$payment_data['NotificationURL'] = $this->url->link('payment/sagepay_server/callback', '', 'SSL');
		$payment_data['TxType'] = $this->config->get('sagepay_server_transaction');

		$payment_data['BillingSurname'] = substr($order_info['payment_lastname'], 0, 20);
		$payment_data['BillingFirstnames'] = substr($order_info['payment_firstname'], 0, 20);
		$payment_data['BillingAddress1'] = substr($order_info['payment_address_1'], 0, 100);

		if ($order_info['payment_address_2']) {
			$payment_data['BillingAddress2'] = $order_info['payment_address_2'];
		}

		$payment_data['BillingCity'] = substr($order_info['payment_city'], 0, 40);
		$payment_data['BillingPostCode'] = substr($order_info['payment_postcode'], 0, 10);
		$payment_data['BillingCountry'] = $order_info['payment_iso_code_2'];

		if ($order_info['payment_iso_code_2'] == 'US') {
			$payment_data['BillingState'] = $order_info['payment_zone_code'];
		}

		$payment_data['BillingPhone'] = substr($order_info['telephone'], 0, 20);

		if ($this->cart->hasShipping()) {
			$payment_data['DeliverySurname'] = substr($order_info['shipping_lastname'], 0, 20);
			$payment_data['DeliveryFirstnames'] = substr($order_info['shipping_firstname'], 0, 20);
			$payment_data['DeliveryAddress1'] = substr($order_info['shipping_address_1'], 0, 100);

			if ($order_info['shipping_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['shipping_address_2'];
			}

			$payment_data['DeliveryCity'] = substr($order_info['shipping_city'], 0, 40);
			$payment_data['DeliveryPostCode'] = substr($order_info['shipping_postcode'], 0, 10);
			$payment_data['DeliveryCountry'] = $order_info['shipping_iso_code_2'];

			if ($order_info['shipping_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['shipping_zone_code'];
			}

			$payment_data['CustomerName'] = substr($order_info['firstname'] . ' ' . $order_info['lastname'], 0, 100);
			$payment_data['DeliveryPhone'] = substr($order_info['telephone'], 0, 20);
		} else {
			$payment_data['DeliveryFirstnames'] = $order_info['payment_firstname'];
			$payment_data['DeliverySurname'] = $order_info['payment_lastname'];
			$payment_data['DeliveryAddress1'] = $order_info['payment_address_1'];

			if ($order_info['payment_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['payment_address_2'];
			}

			$payment_data['DeliveryCity'] = $order_info['payment_city'];
			$payment_data['DeliveryPostCode'] = $order_info['payment_postcode'];
			$payment_data['DeliveryCountry'] = $order_info['payment_iso_code_2'];

			if ($order_info['payment_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['payment_zone_code'];
			}

			$payment_data['DeliveryPhone'] = $order_info['telephone'];
		}

		$payment_data['CustomerEMail'] = substr($order_info['email'], 0, 255);
		$payment_data['Apply3DSecure'] = '0';
		$payment_data['ClientIPAddress'] = $this->request->server['REMOTE_ADDR'];

		if (isset($this->request->post['CreateToken'])) {
			$payment_data['CreateToken'] = $this->request->post['CreateToken'];
			$payment_data['StoreToken'] = 1;
		}

		if (isset($this->request->post['Token'])) {
			$payment_data['Token'] = $this->request->post['Token'];
			$payment_data['StoreToken'] = 1;
		}

		$response_data = $this->model_payment_sagepay_server->sendCurl($url, $payment_data);

		$json = array();

		if ((substr($response_data['Status'], 0, 2) == "OK") || $response_data['Status'] == 'AUTHENTICATED' || $response_data['Status'] == 'REGISTERED') {
			$json['redirect'] = $response_data['NextURL'];
			$json['Status'] = $response_data['Status'];
			$json['StatusDetail'] = $response_data['StatusDetail'];

			$response_data['order_id'] = $this->session->data['order_id'];
			$response_data['VendorTxCode'] = $payment_data['VendorTxCode'];

			$order_info = array_merge($order_info, $response_data);

			$this->model_payment_sagepay_server->addOrder($order_info);

			if ($this->config->get('sagepay_server_transaction') == 'PAYMENT') {
				$recurring_products = $this->cart->getRecurringProducts();

				//loop through any products that are recurring items
				foreach ($recurring_products as $item) {
					$this->model_payment_sagepay_server->addRecurringPayment($item, $payment_data['VendorTxCode']);
				}
			}
		} else {
			$json['error'] = $response_data['StatusDetail'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function callback() {
		$this->load->model('checkout/order');
		$this->load->model('payment/sagepay_server');

		$success_page = $this->url->link('payment/sagepay_server/success', '', 'SSL');
		$error_page = $this->url->link('payment/sagepay_server/failure', '', 'SSL');
		$end_ln = chr(13) . chr(10);

		if (isset($this->request->post['VendorTxCode'])) {
			$vendor_tx_code = $this->request->post['VendorTxCode'];
			$order_id_parts = explode('T', $this->request->post['VendorTxCode']);
			$order_id = (int)$order_id_parts[0];
		} else {
			$vendor_tx_code = '';
			$order_id = '';
		}

		if (isset($this->request->post['Status'])) {
			$str_status = $this->request->post['Status'];
		} else {
			$str_status = '';
		}

		if (isset($this->request->post['VPSSignature'])) {
			$str_vps_signature = $this->request->post['VPSSignature'];
		} else {
			$str_vps_signature = '';
		}
		if (isset($this->request->post['StatusDetail'])) {
			$str_status_detail = $this->request->post['StatusDetail'];
		} else {
			$str_status_detail = '';
		}

		if (isset($this->request->post['VPSTxId'])) {
			$str_vps_tx_id = $this->request->post['VPSTxId'];
		} else {
			$str_vps_tx_id = '';
		}

		if (isset($this->request->post['TxAuthNo'])) {
			$str_tx_auth_no = $this->request->post['TxAuthNo'];
		} else {
			$str_tx_auth_no = '';
		}

		if (isset($this->request->post['AVSCV2'])) {
			$str_avs_cv2 = $this->request->post['AVSCV2'];
		} else {
			$str_avs_cv2 = '';
		}

		if (isset($this->request->post['AddressResult'])) {
			$str_address_result = $this->request->post['AddressResult'];
		} else {
			$str_address_result = '';
		}

		if (isset($this->request->post['PostCodeResult'])) {
			$str_postcode_result = $this->request->post['PostCodeResult'];
		} else {
			$str_postcode_result = '';
		}

		if (isset($this->request->post['CV2Result'])) {
			$str_cv2_result = $this->request->post['CV2Result'];
		} else {
			$str_cv2_result = '';
		}

		if (isset($this->request->post['GiftAid'])) {
			$str_gift_aid = $this->request->post['GiftAid'];
		} else {
			$str_gift_aid = '';
		}

		if (isset($this->request->post['3DSecureStatus'])) {
			$str_3d_secure_status = $this->request->post['3DSecureStatus'];
		} else {
			$str_3d_secure_status = '';
		}

		if (isset($this->request->post['CAVV'])) {
			$str_cavv = $this->request->post['CAVV'];
		} else {
			$str_cavv = '';
		}

		if (isset($this->request->post['AddressStatus'])) {
			$str_address_status = $this->request->post['AddressStatus'];
		} else {
			$str_address_status = '';
		}

		if (isset($this->request->post['PayerStatus'])) {
			$str_payer_status = $this->request->post['PayerStatus'];
		} else {
			$str_payer_status = '';
		}

		if (isset($this->request->post['CardType'])) {
			$str_card_type = $this->request->post['CardType'];
		} else {
			$str_card_type = '';
		}

		if (isset($this->request->post['Last4Digits'])) {
			$str_last_4_digits = $this->request->post['Last4Digits'];
		} else {
			$str_last_4_digits = '';
		}

		if (isset($this->request->post['ExpiryDate'])) {
			$str_expiry_date = $this->request->post['ExpiryDate'];
		} else {
			$str_expiry_date = '';
		}

		if (isset($this->request->post['Token'])) {
			$str_token = $this->request->post['Token'];
		} else {
			$str_token = '';
		}

		if (isset($this->request->post['DeclineCode'])) {
			$str_decline_code = $this->request->post['DeclineCode'];
		} else {
			$str_decline_code = '';
		}

		if (isset($this->request->post['BankAuthCode'])) {
			$str_bank_auth_code = $this->request->post['BankAuthCode'];
		} else {
			$str_bank_auth_code = '';
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$transaction_info = $this->model_payment_sagepay_server->getOrder($order_id);

		//Check if order we have saved in database maches with callback sagepay does
		if (!isset($transaction_info['order_id']) || $transaction_info['order_id'] != $order_id) {
			echo "Status=INVALID" . $end_ln;
			echo "StatusDetail= Order IDs could not be matched. Order might be tampered with." . $end_ln;
			echo "RedirectURL=" . $error_page . $end_ln;

			$this->model_payment_sagepay_server->logger('StatusDetail= Order IDs could not be matched. Order might be tampered with.');

			return;
		}

		if (isset($transaction_info['SecurityKey'])) {
			$str_security_key = $transaction_info['SecurityKey'];
		} else {
			$str_security_key = '';
		}

		/** Now we rebuilt the POST message, including our security key, and use the MD5 Hash **
		 * * component that is included to create our own signature to compare with **
		 * * the contents of the VPSSignature field in the POST.  Check the Sage Pay Server protocol **
		 * * if you need clarification on this process * */
		$str_message = $str_vps_tx_id . $vendor_tx_code . $str_status . $str_tx_auth_no . $this->config->get('sagepay_server_vendor') . urldecode($str_avs_cv2) . $str_security_key
				. $str_address_result . $str_postcode_result . $str_cv2_result . $str_gift_aid . $str_3d_secure_status . $str_cavv
				. $str_address_status . $str_payer_status . $str_card_type . $str_last_4_digits . $str_decline_code . $str_expiry_date . $str_bank_auth_code;

		$str_my_signature = strtoupper(md5($str_message));

		/** We can now compare our MD5 Hash signature with that from Sage Pay Server * */
		if ($str_my_signature != $str_vps_signature) {
			$this->model_payment_sagepay_server->deleteOrder($order_id);

			echo "Status=INVALID" . $end_ln;
			echo "StatusDetail= Cannot match the MD5 Hash. Order might be tampered with." . $end_ln;
			echo "RedirectURL=" . $error_page . $end_ln;

			$this->model_payment_sagepay_server->logger('StatusDetail= Cannot match the MD5 Hash. Order might be tampered with.');

			return;
		}

		if ($str_status != "OK" || !$order_info) {
			$this->model_payment_sagepay_server->deleteOrder($order_id);

			echo "Status=INVALID" . $end_ln;
			echo "StatusDetail= Either status invalid or order info was not found.";
			echo "RedirectURL=" . $error_page . $end_ln;

			$this->model_payment_sagepay_server->logger('StatusDetail= Either status invalid or order info was not found.');

			return;
		}

		$comment = "Paid with Sagepay Server<br><br>";
		$comment .= "<b>Transaction details</b><br>";
		$comment .= "Status: " . $str_status . "<br>";
		$comment .= "AVS and CV2 checks: " . $str_avs_cv2 . "<br>";
		$comment .= "3D Secure checks: " . $str_3d_secure_status . "<br>";
		$comment .= "Card type: " . $str_card_type . "<br>";

		if ($str_card_type == "PAYPAL") {
			$comment .= "Paypal address status: " . $str_address_status . "<br>";
			$comment .= "Paypal payer status: " . $str_payer_status . "<br>";
		} else {
			$comment .= "Last 4 digits: " . $str_last_4_digits . "<br>";

			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('sagepay_server_order_status_id'), $comment);

			$this->model_payment_sagepay_server->updateOrder($order_info, $str_vps_tx_id, $str_tx_auth_no);

			$this->model_payment_sagepay_server->addTransaction($transaction_info['sagepay_server_order_id'], $this->config->get('sagepay_server_transaction'), $order_info);

			if (!empty($str_token)) {
				$data = array();
				$data['customer_id'] = $order_info['customer_id'];
				$data['ExpiryDate'] = substr($str_expiry_date, -4, 2) . '/' . substr($str_expiry_date, 2);
				$data['Token'] = $str_token;
				$data['CardType'] = $str_card_type;
				$data['Last4Digits'] = $str_last_4_digits;

				$this->model_payment_sagepay_server->addCard($data);
			}

			echo "Status=OK" . $end_ln;
			echo "RedirectURL=" . $success_page . $end_ln;
		}
	}

	public function success() {
		$this->load->model('checkout/order');
		$this->load->model('payment/sagepay_server');
		$this->load->model('checkout/recurring');

		if (isset($this->session->data['order_id'])) {
			$order_details = $this->model_payment_sagepay_server->getOrder($this->session->data['order_id']);

			if ($this->config->get('sagepay_server_transaction') == 'PAYMENT') {
				$recurring_products = $this->model_payment_sagepay_server->getRecurringOrders($this->session->data['order_id']);

				//loop through any products that are recurring items
				foreach ($recurring_products as $item) {
					$this->model_payment_sagepay_server->updateRecurringPayment($item, $order_details);
				}
			}

			$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		} else {
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
	}

	public function failure() {
		$this->load->language('payment/sagepay_server');

		$this->session->data['error'] = $this->language->get('text_generic_error');

		$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
	}

	public function cron() {
		if ($this->request->get['token'] == $this->config->get('sagepay_server_cron_job_token')) {
			$this->load->model('payment/sagepay_server');

			$orders = $this->model_payment_sagepay_server->cronPayment();

			$this->model_payment_sagepay_server->updateCronJobRunTime();

			$this->model_payment_sagepay_server->logger('Repeat Orders: ' . print_r($orders, 1));
		}
	}
}