<?php
class ControllerExtensionCreditCardSagepayServer extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/sagepay_server');

		$this->load->model('extension/payment/sagepay_server');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);


		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_type'] = $this->language->get('column_type');
		$data['column_digits'] = $this->language->get('column_digits');
		$data['column_expiry'] = $this->language->get('column_expiry');

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_new_card'] = $this->language->get('button_new_card');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');

		if ($this->config->get('sagepay_server_card')) {
			$data['cards'] = $this->model_extension_payment_sagepay_server->getCards($this->customer->getId());
			$data['delete'] = $this->url->link('extension/credit_card/sagepay_server/delete', 'card_id=', true);

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$cards_total = count($data['cards']);

			$pagination = new Pagination();
			$pagination->total = $cards_total;
			$pagination->page = $page;
			$pagination->limit = 10;
			$pagination->url = $this->url->link('extension/credit_card/sagepay_server', 'page={page}', true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($cards_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($cards_total - 10)) ? $cards_total : ((($page - 1) * 10) + 10), $cards_total, ceil($cards_total / 10));
		} else {
			$data['cards'] = false;
			$data['pagination'] = false;
			$data['results'] = false;
		}

		$data['back'] = $this->url->link('account/account', '', true);
		$data['add'] = $this->url->link('extension/credit_card/sagepay_server/add', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/credit_card/sagepay_server_list', $data));
	}

	public function delete() {
		$this->load->language('extension/credit_card/sagepay_server');

		$this->load->model('extension/payment/sagepay_server');

		$card = $this->model_extension_payment_sagepay_server->getCard($this->request->get['card_id'], '');

		if (!empty($card['token'])) {
			if ($this->config->get('sagepay_server_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/removetoken.vsp';
			} else {
				$url = 'https://test.sagepay.com/gateway/service/removetoken.vsp';
			}
			
			$payment_data['VPSProtocol'] = '3.00';
			$payment_data['Vendor'] = $this->config->get('sagepay_server_vendor');
			$payment_data['TxType'] = 'REMOVETOKEN';
			$payment_data['Token'] = $card['token'];

			$response_data = $this->model_extension_payment_sagepay_server->sendCurl($url, $payment_data);
			
			if ($response_data['Status'] == 'OK') {
				$this->model_extension_payment_sagepay_server->deleteCard($this->request->get['card_id']);
				$this->session->data['success'] = $this->language->get('text_success_card');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_fail_card');
			}
		} else {
			$this->session->data['error_warning'] = $this->language->get('text_fail_card');
		}
		$this->response->redirect($this->url->link('extension/credit_card/sagepay_server', '', true));
	}

	public function addCard() {
		$this->load->language('extension/payment/sagepay_server');
		
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/sagepay_server');

		$payment_data = array();

		if ($this->config->get('sagepay_server_test') == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/token.vsp';
		} else {
			$url = 'https://test.sagepay.com/gateway/service/token.vsp';
		}
		$payment_data['VPSProtocol'] = '3.00';

		$payment_data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$payment_data['TxType'] = 'TOKEN';
		$payment_data['Vendor'] = $this->config->get('sagepay_server_vendor');
		$payment_data['VendorTxCode'] = 'server_card_' . strftime("%Y%m%d%H%M%S") . mt_rand(1, 999);
		$payment_data['NotificationURL'] = $this->url->link('extension/credit_card/sagepay_server/callback', '', true);
		$payment_data['Currency'] = $this->session->data['currency'];

		$response_data = $this->model_extension_payment_sagepay_server->sendCurl($url, $payment_data);

		$this->model_extension_payment_sagepay_server->logger('Response', $response_data);

		if ($response_data['Status'] == 'OK') {
			$json['redirect'] = $response_data['NextURL'];
			$json['Status'] = $response_data['Status'];
			$json['StatusDetail'] = $response_data['StatusDetail'];
			
			$order_info['order_id'] = -1;
			$order_info['VPSTxId'] = substr($response_data['VPSTxId'], 1, -1);
			$order_info['SecurityKey'] = $response_data['SecurityKey'];
			$order_info['VendorTxCode'] = $payment_data['VendorTxCode'];
			$order_info['currency_code'] = $this->session->data['currency'];
			$order_info['total'] = '';
			$this->model_extension_payment_sagepay_server->addOrder($order_info);
		} else {
			$json['error'] = $response_data['StatusDetail'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function callback() {
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/sagepay_server');

		$this->model_extension_payment_sagepay_server->logger('Callback data', $this->request->post);

		$success_page = $this->url->link('extension/credit_card/sagepay_server/success', '', true);
		$error_page = $this->url->link('extension/credit_card/sagepay_server/failure', '', true);
		$end_ln = chr(13) . chr(10);

		if (isset($this->request->post['VendorTxCode'])) {
			$vendor_tx_code = $this->request->post['VendorTxCode'];
		} else {
			$vendor_tx_code = '';
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

		if (isset($this->request->post['VPSTxId'])) {
			$str_vps_tx_id = $this->request->post['VPSTxId'];
		} else {
			$str_vps_tx_id = '';
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

		$transaction_info = $this->model_extension_payment_sagepay_server->getOrder('', $str_vps_tx_id);

		if (isset($transaction_info['SecurityKey'])) {
			$str_security_key = $transaction_info['SecurityKey'];
		} else {
			$str_security_key = '';
		}
		$this->model_extension_payment_sagepay_server->logger('$transaction_info', $transaction_info);
		$this->model_extension_payment_sagepay_server->logger('$str_vps_tx_id', $str_vps_tx_id);
		$this->model_extension_payment_sagepay_server->logger('$vendor_tx_code', $vendor_tx_code);
		$this->model_extension_payment_sagepay_server->logger('$str_status', $str_status);
		$this->model_extension_payment_sagepay_server->logger('sagepay_server_vendor', $this->config->get('sagepay_server_vendor'));
		$this->model_extension_payment_sagepay_server->logger('$str_token', $str_token);
		$this->model_extension_payment_sagepay_server->logger('$str_security_key', $str_security_key);

		$str_message = $str_vps_tx_id . $vendor_tx_code . $str_status . strtolower($this->config->get('sagepay_server_vendor')) . $str_token . $str_security_key;

		$str_my_signature = strtoupper(md5($str_message));

		/** We can now compare our MD5 Hash signature with that from Sage Pay Server * */
		if ($str_my_signature != $str_vps_signature) {

			echo "Status=INVALID" . $end_ln;
			echo "StatusDetail= Cannot match the MD5 Hash. Order might be tampered with." . $end_ln;
			echo "RedirectURL=" . $error_page . $end_ln;
			$this->model_extension_payment_sagepay_server->logger('StatusDetail', 'Cannot match the MD5 Hash. Order might be tampered with.');
			exit;
		}

		if ($str_status != "OK") {
			echo "Status=INVALID" . $end_ln;
			echo "StatusDetail= Either status invalid or order info was not found.";
			echo "RedirectURL=" . $error_page . $end_ln;

			$this->model_extension_payment_sagepay_server->logger('StatusDetail', 'Either status invalid or order info was not found.');

			exit;
		}

		$card_data['customer_id'] = $transaction_info['customer_id'];
		$card_data['Token'] = $this->request->post['Token'];
		$card_data['Last4Digits'] = $this->request->post['Last4Digits'];
		$card_data['ExpiryDate'] = substr_replace($this->request->post['ExpiryDate'], '/', 2, 0);
		$card_data['CardType'] = $this->request->post['CardType'];
		$this->model_extension_payment_sagepay_server->addCard($card_data);

		echo "Status=OK" . $end_ln;
		echo "RedirectURL=" . $success_page . $end_ln;
	}

	public function success() {
		$this->load->model('extension/payment/sagepay_server');
		$this->model_extension_payment_sagepay_server->logger('Success', '');
		$this->session->data['success'] = 'Success';
		$this->response->redirect($this->url->link('extension/credit_card/sagepay_server', '', true));
	}

	public function failure() {
		$this->load->model('extension/payment/sagepay_server');
		$this->model_extension_payment_sagepay_server->logger('Failure', '');
		$this->session->data['error_warning'] = 'Failure';
		$this->response->redirect($this->url->link('extension/credit_card/sagepay_server', '', true));
	}
}