<?php
class ControllerAccountGdpr extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/gdpr', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/gdpr_data');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/gdpr_data', 'language=' . $this->config->get('config_language'))
		);

		$data['firstname'] = $this->customer->getFirstname();
		$data['lastname'] = $this->customer->getFirstname();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getTelephone();

		// Addresses
		$data['addresses'] = array();

		$this->load->model('account/address');

		$results = $this->model_account_address->getAddresses($this->customer->getId());

		foreach ($results as $result) {
			$address = array(
				'firstname' => $result['firstname'],
				'lastname'  => $result['lastname'],
				'address_1' => $result['address_1'],
				'address_2' => $result['address_2'],
				'city'      => $result['city'],
				'postcode'  => $result['postcode'],
				'country'   => $result['country'],
				'zone'      => $result['zone']
			);

			if (!in_array($address, $data['addresses'])) {
				$data['addresses'][] = $address;
			}
		}

		// Order Addresses
		$this->load->model('account/order');

		$results = $this->model_account_order->getOrders($this->customer->getId());

		foreach ($results as $result) {
			$address = array(
				'firstaname' => $result['payment_firstaname'],
				'lastname'   => $result['payment_lastname'],
				'address_1'  => $result['payment_address_1'],
				'address_2'  => $result['payment_address_2'],
				'city'       => $result['payment_city'],
				'postcode'   => $result['payment_postcode'],
				'country'    => $result['payment_country'],
				'zone'       => $result['payment_zone']
			);

			if (!in_array($address, $data['addresses'])) {
				$data['addresses'][] = $address;
			}

			$address = array(
				'firstname' => $result['shipping_firstname'],
				'lastname'  => $result['shipping_lastname'],
				'address_1' => $result['shipping_address_1'],
				'address_2' => $result['shipping_address_2'],
				'city'      => $result['shipping_city'],
				'postcode'  => $result['shipping_postcode'],
				'country'   => $result['shipping_country'],
				'zone'      => $result['shipping_zone']
			);

			if (!in_array($address, $data['addresses'])) {
				$data['addresses'][] = $address;
			}
		}

		// Ip's
		$data['ips'] = array();

		$this->load->model('account/customer');

		$results = $this->model_account_customer->getIps($this->customer->getId());

		foreach ($results as $result) {
			$data['ips'][] = array(
				'ip'        => $result['ip'],
				'country'   => $result['country'],
				'date_added'=> date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$data['delete'] = $this->url->link('account/gdpr/delete', 'language=' . $this->config->get('config_language'));
		$data['cancel'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/gdpr_data', $data));
	}

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/gdpr/delete', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/gdpr_delete');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/gdpr/delete', 'language=' . $this->config->get('config_language'))
		);

		$data['text_confirm'] = sprintf($this->language->get('text_confirm'), $this->config->get('config_gdpr_limit'));
		$data['text_confirm'] = sprintf($this->language->get('text_confirm'), $this->config->get('config_gdpr_limit'));

		$data['delete'] = $this->url->link('account/gdpr/success', 'language=' . $this->config->get('config_language'));
		$data['cancel'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/gdpr_delete', $data));
	}

	public function success() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/gdpr/delete', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->model('account/gdpr');

		$gdpr_info = $this->model_account_gdpr->getGdpr($this->customer->getId());

		if (!$gdpr_info) {
			$this->model_account_gdpr->addGdpr($this->customer->getId());
		}

		$this->load->language('account/gdpr_success');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/gdpr/delete', 'language=' . $this->config->get('config_language'))
		);



		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/gdpr_success', $data));
	}
}