<?php
class ControllerExtensionCreditCardSquareup extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/squareup');
		$this->load->model('account/address');
		$this->load->library('squareup');

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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/credit_card/squareup', '', true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		} 

		$data['cards'] = array();

		$error = '';

		try {
			if ($this->config->get('payment_squareup_enable_sandbox')) {
				$access_token = $this->config->get('payment_squareup_sandbox_token');
			} else {
				$access_token = $this->config->get('payment_squareup_access_token');
			}

			$email = $this->customer->getEmail();
			$address_id = $this->customer->getAddressId();
			$address = $this->model_account_address->getAddress($address_id);
			$country_code = $address['iso_code_2'];
			$phone = $this->squareup->phoneFormat($this->customer->getTelephone(), $country_code);
			$customers = $this->squareup->searchCustomers($access_token, $email, $phone);

			if (!empty($customers['customers'][0])) {
				$cards = $this->squareup->listCards($access_token, $customers['customers'][0]['id']);
				if (!empty($cards['cards'])) {
					foreach ($cards['cards'] as $card) {
						$data['cards'][] = array(
							'text' => sprintf($this->language->get('text_card_ends_in'), $card['card_brand'], $card['last_4'], date($this->language->get('datetime_format'), strtotime($card['created_at']))),
							'disable' => $this->url->link('extension/credit_card/squareup/forget', 'card_id=' . $card['id'], true)
						);
					}
				}
			}
		} catch (\Squareup\Exception $e) {
			if ($e->isCurlError()) {
				$error = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenRevoked()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenRevokedEmail();

				$error = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenExpired()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenExpiredEmail();

				$error = $this->language->get('text_token_issue_customer_error');
			} else {
				$error = $e->getMessage();
			}
		}

		if ($error) {
			if ($data['error']) {
				$data['error'] .= "<br>\n".$error;
			} else {
				$data['error'] = $error;
			}
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('extension/credit_card/squareup', $data));
	}

	public function forget() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/squareup');
		$this->load->model('account/address');
		$this->load->library('squareup');

		$card_id = !empty($this->request->get['card_id']) ? $this->request->get['card_id'] : '';

		$error = '';

		try {
			if ($this->config->get('payment_squareup_enable_sandbox')) {
				$access_token = $this->config->get('payment_squareup_sandbox_token');
			} else {
				$access_token = $this->config->get('payment_squareup_access_token');
			}

			$email = $this->customer->getEmail();
			$address_id = $this->customer->getAddressId();
			$address = $this->model_account_address->getAddress($address_id);
			$country_code = $address['iso_code_2'];
			$phone = $this->squareup->phoneFormat($this->customer->getTelephone(), $country_code);
			$customers = $this->squareup->searchCustomers($access_token, $email, $phone);

			if (!empty($customers['customers'][0])) {
				$cards = $this->squareup->listCards($access_token, $customers['customers'][0]['id']);
				$found = false;
				if (!empty($cards['cards'])) {
					foreach ($cards['cards'] as $card) {
						if ($card['id'] == $card_id) {
							$this->squareup->disableCard($access_token, $card_id);
							$this->session->data['success'] = $this->language->get('text_success_card_delete');
							$found = true;
 							break;
						}
					}
				} else {
					$error = $this->language->get('error_card');
				}
			} else {
				$error = str_replace('%2', $phone, str_replace('%1', $email, $this->language->get('error_customer')));
			}
		} catch (\Squareup\Exception $e) {
			if ($e->isCurlError()) {
				$error = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenRevoked()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenRevokedEmail();

				$error = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenExpired()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenExpiredEmail();

				$error = $this->language->get('text_token_issue_customer_error');
			} else {
				$error = $e->getMessage();
			}
		}

		if ($error) {
			$this->session->data['error'] = $error;
		}

		$this->response->redirect($this->url->link('extension/credit_card/squareup', '', true));
	}
}