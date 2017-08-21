<?php
class ControllerExtensionOpenbayEtsy extends Controller {
	public function install() {
		$this->load->language('extension/openbay/etsy');
		$this->load->model('extension/openbay/etsy');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/openbay/etsy_product');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/openbay/etsy_product');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/openbay/etsy_shipping');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/openbay/etsy_shipping');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/openbay/etsy_shop');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/openbay/etsy_shop');

		$this->model_extension_openbay_etsy->install();
	}

	public function uninstall() {
		$this->load->model('extension/openbay/etsy');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_extension_openbay_etsy->uninstall();
		$this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
	}

	public function index() {
		$data = $this->load->language('extension/openbay/etsy');

		$this->document->setTitle($this->language->get('text_dashboard'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_dashboard'),
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		$data['validation'] 	= $this->openbay->etsy->validate();
		$data['links_settings'] = $this->url->link('extension/openbay/etsy/settings', 'user_token=' . $this->session->data['user_token'], true);
		$data['links_products'] = $this->url->link('extension/openbay/etsy_product/links', 'user_token=' . $this->session->data['user_token'], true);
		$data['links_listings'] = $this->url->link('extension/openbay/etsy_product/listings', 'user_token=' . $this->session->data['user_token'], true);
		$data['link_signup']    = 'https://account.openbaypro.com/etsy/apiRegister/?endpoint=2&utm_source=opencart_install&utm_medium=dashboard&utm_campaign=etsy';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/etsy', $data));
	}

	public function settings() {
		$this->load->model('setting/setting');
		$this->load->model('extension/openbay/etsy');
		$this->load->model('localisation/order_status');

		$data = $this->load->language('extension/openbay/etsy_settings');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('etsy', $this->request->post);

			$this->openbay->etsy->resetConfig($this->request->post['etsy_token'], $this->request->post['etsy_encryption_key']);

			$account_info = $this->model_extension_openbay_etsy->verifyAccount();

			if (isset($account_info['header_code']) && $account_info['header_code'] == 200) {
				$this->openbay->etsy->settingsUpdate();

				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['error'] = $this->language->get('error_account_info');
			}

			$this->response->redirect($this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_etsy'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy/settings', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$data['action'] = $this->url->link('extension/openbay/etsy/settings', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->post['etsy_status'])) {
			$data['etsy_status'] = $this->request->post['etsy_status'];
		} else {
			$data['etsy_status'] = $this->config->get('etsy_status');
		}

		if (isset($this->request->post['etsy_token'])) {
			$data['etsy_token'] = $this->request->post['etsy_token'];
		} else {
			$data['etsy_token'] = $this->config->get('etsy_token');
		}

		if (isset($this->request->post['etsy_encryption_key'])) {
			$data['etsy_encryption_key'] = $this->request->post['etsy_encryption_key'];
		} else {
			$data['etsy_encryption_key'] = $this->config->get('etsy_encryption_key');
		}

		if (isset($this->request->post['etsy_encryption_iv'])) {
			$data['etsy_encryption_iv'] = $this->request->post['etsy_encryption_iv'];
		} else {
			$data['etsy_encryption_iv'] = $this->config->get('etsy_encryption_iv');
		}

		if (isset($this->request->post['etsy_address_format'])) {
			$data['etsy_address_format'] = $this->request->post['etsy_address_format'];
		} else {
			$data['etsy_address_format'] = $this->config->get('etsy_address_format');
		}

		if (isset($this->request->post['etsy_order_status_new'])) {
			$data['etsy_order_status_new'] = $this->request->post['etsy_order_status_new'];
		} else {
			$data['etsy_order_status_new'] = $this->config->get('etsy_order_status_new');
		}

		if (isset($this->request->post['etsy_order_status_paid'])) {
			$data['etsy_order_status_paid'] = $this->request->post['etsy_order_status_paid'];
		} else {
			$data['etsy_order_status_paid'] = $this->config->get('etsy_order_status_paid');
		}

		if (isset($this->request->post['etsy_order_status_shipped'])) {
			$data['etsy_order_status_shipped'] = $this->request->post['etsy_order_status_shipped'];
		} else {
			$data['etsy_order_status_shipped'] = $this->config->get('etsy_order_status_shipped');
		}

		if (isset($this->request->post['etsy_logging'])) {
			$data['etsy_logging'] = $this->request->post['etsy_logging'];
		} else {
			$data['etsy_logging'] = $this->config->get('etsy_logging');
		}

		$data['api_server'] = $this->openbay->etsy->getServer();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['account_info'] = $this->model_extension_openbay_etsy->verifyAccount();
		$data['link_signup'] = 'https://account.openbaypro.com/etsy/apiRegister/?endpoint=2&utm_source=opencart_install&utm_medium=settings&utm_campaign=etsy';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/etsy_settings', $data));
	}

	public function settingsUpdate() {
		$this->openbay->etsy->settingsUpdate();

		$response = array('header_code' => 200);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	public function getOrders() {
		$response = $this->openbay->etsy->call('v1/etsy/order/get/all/', 'GET');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($response));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/openbay/etsy')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
