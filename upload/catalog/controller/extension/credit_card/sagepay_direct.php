<?php
class ControllerExtensionCreditCardSagepayDirect extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/sagepay_direct');

		$this->load->model('extension/payment/sagepay_direct');

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

		if ($this->config->get('payment_sagepay_direct_card')) {
			$data['cards'] = $this->model_extension_payment_sagepay_direct->getCards($this->customer->getId());
			$data['delete'] = $this->url->link('extension/credit_card/sagepay_direct/delete', 'card_id=', true);

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
			$pagination->url = $this->url->link('extension/credit_card/sagepay_direct', 'page={page}', true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($cards_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($cards_total - 10)) ? $cards_total : ((($page - 1) * 10) + 10), $cards_total, ceil($cards_total / 10));
		} else {
			$data['cards'] = false;
			$data['pagination'] = false;
			$data['results'] = false;
		}

		$data['back'] = $this->url->link('account/account', '', true);
		$data['add'] = $this->url->link('extension/credit_card/sagepay_direct/add', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/credit_card/sagepay_direct_list', $data));
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/sagepay_direct');

		$this->load->model('extension/payment/sagepay_direct');

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
		
		$data['add'] = $this->url->link('extension/credit_card/sagepay_direct/addCard', '', true);
		$data['back'] = $this->url->link('extension/credit_card/sagepay_direct', '', true);

		$data['cards'] = array();

		$data['cards'][] = array(
			'text' => 'Visa',
			'value' => 'VISA'
		);

		$data['cards'][] = array(
			'text' => 'MasterCard',
			'value' => 'MC'
		);

		$data['cards'][] = array(
			'text' => 'Visa Delta/Debit',
			'value' => 'DELTA'
		);

		$data['cards'][] = array(
			'text' => 'Solo',
			'value' => 'SOLO'
		);

		$data['cards'][] = array(
			'text' => 'Maestro',
			'value' => 'MAESTRO'
		);

		$data['cards'][] = array(
			'text' => 'Visa Electron UK Debit',
			'value' => 'UKE'
		);

		$data['cards'][] = array(
			'text' => 'American Express',
			'value' => 'AMEX'
		);

		$data['cards'][] = array(
			'text' => 'Diners Club',
			'value' => 'DC'
		);

		$data['cards'][] = array(
			'text' => 'Japan Credit Bureau',
			'value' => 'JCB'
		);

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_valid'] = array();

		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {
			$data['year_valid'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/credit_card/sagepay_direct_form', $data));
	}

	public function delete() {
		$this->load->language('extension/credit_card/sagepay_direct');
		$this->load->model('extension/payment/sagepay_direct');

		$card = $this->model_extension_payment_sagepay_direct->getCard($this->request->get['card_id'], false);

		if (!empty($card['token'])) {
			if ($this->config->get('payment_sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/removetoken.vsp';
			} else {
				$url = 'https://test.sagepay.com/gateway/service/removetoken.vsp';
			}
			
			$payment_data['VPSProtocol'] = '3.00';
			$payment_data['Vendor'] = $this->config->get('payment_sagepay_direct_vendor');
			$payment_data['TxType'] = 'REMOVETOKEN';
			$payment_data['Token'] = $card['token'];

			$response_data = $this->model_extension_payment_sagepay_direct->sendCurl($url, $payment_data);
			
			if ($response_data['Status'] == 'OK') {
				$this->model_extension_payment_sagepay_direct->deleteCard($this->request->get['card_id']);
				
				$this->session->data['success'] = $this->language->get('text_success_card');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_fail_card');
			}
		} else {
			$this->session->data['error_warning'] = $this->language->get('text_fail_card');
		}
		
		$this->response->redirect($this->url->link('acredit_card/sagepay_direct', '', true));
	}

	public function addCard() {
		$this->load->language('extension/credit_card/sagepay_direct');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/sagepay_direct');

		$payment_data = array();

		if ($this->config->get('payment_sagepay_direct_test') == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/directtoken.vsp';
		} else {
			$url = 'https://test.sagepay.com/gateway/service/directtoken.vsp';
		}
		$payment_data['VPSProtocol'] = '3.00';

		$payment_data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$payment_data['TxType'] = 'TOKEN';
		$payment_data['Vendor'] = $this->config->get('payment_sagepay_direct_vendor');
		$payment_data['Currency'] = $this->session->data['currency'];
		$payment_data['CardHolder'] = $this->request->post['cc_owner'];
		$payment_data['CardNumber'] = $this->request->post['cc_number'];
		$payment_data['ExpiryDate'] = $this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], 2);
		$payment_data['CV2'] = $this->request->post['cc_cvv2'];
		$payment_data['CardType'] = $this->request->post['cc_type'];

		$response_data = $this->model_extension_payment_sagepay_direct->sendCurl($url, $payment_data);

		if ($response_data['Status'] == 'OK') {
			$card_data = array();
			$card_data['customer_id'] = $this->customer->getId();
			$card_data['Token'] = $response_data['Token'];
			$card_data['Last4Digits'] = substr(str_replace(' ', '', $payment_data['CardNumber']), -4, 4);
			$card_data['ExpiryDate'] = $this->request->post['cc_expire_date_month'] . '/' . substr($this->request->post['cc_expire_date_year'], 2);
			$card_data['CardType'] = $payment_data['CardType'];
			$this->model_extension_payment_sagepay_direct->addCard($card_data);
			$this->session->data['success'] = $this->language->get('text_success_add_card');
		} else {
			$this->session->data['error_warning'] = $response_data['Status'] . ': ' . $response_data['StatusDetail'];
			$this->model_extension_payment_sagepay_direct->logger('Response data: ', $this->session->data['error_warning']);
		}

		$this->response->redirect($this->url->link('extension/credit_card/sagepay_direct', '', true));
	}
}
