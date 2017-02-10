<?php
class ControllerExtensionPaymentDivido extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/divido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/payment/divido');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_divido', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_api_key'] = $this->language->get('entry_api_key');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_productselection'] = $this->language->get('entry_productselection');
		$data['entry_planselection'] = $this->language->get('entry_planselection');
		$data['entry_planlist'] = $this->language->get('entry_planlist');
		$data['entry_price_threshold'] = $this->language->get('entry_price_threshold');
		$data['entry_threshold_list'] = $this->language->get('entry_threshold_list');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_cart_threshold'] = $this->language->get('entry_cart_threshold');

		$data['help_api_key'] = $this->language->get('help_api_key');
		$data['help_status'] = $this->language->get('help_status');
		$data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_title'] = $this->language->get('help_title');
		$data['help_planselection'] = $this->language->get('help_planselection');
		$data['help_productselection'] = $this->language->get('help_productselection');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_cart_threshold'] = $this->language->get('help_cart_threshold');

		$data['entry_plans_options'] = array(
			'all'		=> $this->language->get('entry_plans_options_all'),
			'selected'	=> $this->language->get('entry_plans_options_selected'),
		);

		$data['entry_products_options']= array(
			'all'		=> $this->language->get('entry_products_options_all'),
			'selected'	=> $this->language->get('entry_products_options_selected'),
			'threshold'	=> $this->language->get('entry_products_options_threshold'),
		);

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/divido', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/divido', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL');

		if (isset($this->request->post['payment_divido_api_key'])) {
			$data['payment_divido_api_key'] = $this->request->post['payment_divido_api_key'];
		} else {
			$data['payment_divido_api_key'] = $this->config->get('payment_divido_api_key');
		}

		if (isset($this->request->post['payment_divido_order_status_id'])) {
			$data['payment_divido_order_status_id'] = $this->request->post['payment_divido_order_status_id'];
		} elseif ($this->config->get('payment_divido_order_status_id')) {
			$data['payment_divido_order_status_id'] = $this->config->get('payment_divido_order_status_id');
		} else {
			$data['payment_divido_order_status_id'] = 2;
		}

		if (isset($this->request->post['payment_divido_status'])) {
			$data['payment_divido_status'] = $this->request->post['payment_divido_status'];
		} else {
			$data['payment_divido_status'] = $this->config->get('payment_divido_status');
		}

		if (isset($this->request->post['payment_divido_sort_order'])) {
			$data['payment_divido_sort_order'] = $this->request->post['payment_divido_sort_order'];
		} else {
			$data['payment_divido_sort_order'] = $this->config->get('payment_divido_sort_order');
		}

		if (isset($this->request->post['payment_divido_title'])) {
			$data['payment_divido_title'] = $this->request->post['payment_divido_title'];
		} else {
			$data['payment_divido_title'] = $this->config->get('payment_divido_title');
		}

		if (isset($this->request->post['payment_divido_productselection'])) {
			$data['payment_divido_productselection'] = $this->request->post['payment_divido_productselection'];
		} else {
			$data['payment_divido_productselection'] = $this->config->get('payment_divido_productselection');
		}

		if (isset($this->request->post['payment_divido_price_threshold'])) {
			$data['payment_divido_price_threshold'] = $this->request->post['payment_divido_price_threshold'];
		} else {
			$data['payment_divido_price_threshold'] = $this->config->get('payment_divido_price_threshold');
		}

		if (isset($this->request->post['payment_divido_cart_threshold'])) {
			$data['payment_divido_cart_threshold'] = $this->request->post['payment_divido_cart_threshold'];
		} else {
			$data['payment_divido_cart_threshold'] = $this->config->get('payment_divido_cart_threshold');
		}

		if (isset($this->request->post['payment_divido_planselection'])) {
			$data['payment_divido_planselection'] = $this->request->post['payment_divido_planselection'];
		} else {
			$data['payment_divido_planselection'] = $this->config->get('payment_divido_planselection');
		}

		if (isset($this->request->post['payment_divido_plans_selected'])) {
			$data['payment_divido_plans_selected'] = $this->request->post['payment_divido_plans_selected'];
		} elseif ($this->config->get('payment_divido_plans_selected')) {
			$data['payment_divido_plans_selected'] = $this->config->get('payment_divido_plans_selected');
		} else {
			$data['payment_divido_plans_selected'] = array();
		}

		if (isset($this->request->post['payment_divido_categories'])) {
			$data['payment_divido_categories'] = $this->request->post['payment_divido_categories'];
		} elseif ($this->config->get('payment_divido_categories')) {
			$data['payment_divido_categories'] = $this->config->get('payment_divido_categories');
		} else {
			$data['payment_divido_categories'] = array();
		}

		$data['categories'] = array();

		$this->load->model('catalog/category');

		foreach ($data['payment_divido_categories'] as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['categories'][] = array(
					'category_id' 	=> $category_info['category_id'],
					'name' 			=> ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		try {
			$data['divido_plans'] = $this->model_extension_payment_divido->getAllPlans();
		} catch (Exception $e) {
			$this->log->write($e->getMessage());
			$data['divido_plans'] = array();
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/divido', $data));
	}


	public function order() {
		if (!$this->config->get('payment_divido_status')) {
			return null;
		}

		$this->load->model('extension/payment/divido');
		$this->load->language('extension/payment/divido');

		$order_id = $this->request->get['order_id'];

		$lookup = $this->model_extension_payment_divido->getLookupByOrderId($order_id);
		$proposal_id = null;
		$application_id = null;
		$deposit_amount = null;
		if ($lookup->num_rows == 1) {
			$lookup_data = $lookup->row;
			$proposal_id = $lookup_data['proposal_id'];
			$application_id = $lookup_data['application_id'];
			$deposit_amount = $lookup_data['deposit_amount'];
		}

		$data['text_order_info'] = $this->language->get('text_order_info');
		$data['text_proposal_id'] = $this->language->get('text_proposal_id');
		$data['text_application_id'] = $this->language->get('text_application_id');
		$data['text_deposit_amount'] = $this->language->get('text_deposit_amount');

		$data['proposal_id'] = $proposal_id;
		$data['application_id'] = $application_id;
		$data['deposit_amount'] = $deposit_amount;

		return $this->load->view('extension/payment/divido_order', $data);
	}

	public function install() {
		$this->load->model('extension/payment/divido');
		$this->model_extension_payment_divido->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/divido');
		$this->model_extension_payment_divido->uninstall();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/divido')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
