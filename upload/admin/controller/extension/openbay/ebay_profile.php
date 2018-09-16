<?php
class ControllerExtensionOpenbayEbayProfile extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/openbay/ebay_profile');

        $data = $this->language->all();

		$this->load->model('extension/openbay/ebay_profile');

		$this->document->setTitle($data['heading_title']);
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['add'] = $this->url->link('extension/openbay/ebay_profile/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['types'] = $this->model_extension_openbay_ebay_profile->getTypes();
		$data['profiles'] = $this->model_extension_openbay_ebay_profile->getAll();
		$data['user_token'] = $this->session->data['user_token'];

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
			'href' => $this->url->link('extension/openbay/ebay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_ebay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/ebay_profile_list', $data));

	}

	public function add() {
		$this->load->language('extension/openbay/ebay_profile');

        $data = $this->language->all();

		$this->document->setTitle($data['heading_title']);

        $this->load->model('extension/openbay/ebay_profile');

		if (!isset($this->request->post['step1'])) {
			if ($this->request->post && $this->profileValidate()) {
				$this->session->data['success'] = $data['text_added'];

				$this->model_extension_openbay_ebay_profile->add($this->request->post);

				$this->response->redirect($this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true));
			}
		}

		$this->form($data);
	}

	public function delete() {
		$this->load->model('extension/openbay/ebay_profile');

		if (!$this->user->hasPermission('modify', 'extension/openbay/ebay_profile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['ebay_profile_id'])) {
				$this->model_extension_openbay_ebay_profile->delete($this->request->get['ebay_profile_id']);
			}
		}

		$this->response->redirect($this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function edit() {
		$this->load->language('extension/openbay/ebay_profile');

        $data = $this->language->all();

		$this->document->setTitle($data['heading_title']);

        $this->load->model('extension/openbay/ebay_profile');

		if ($this->request->post && $this->profileValidate()) {
			$this->session->data['success'] = $data['text_updated'];

			$this->model_extension_openbay_ebay_profile->edit($this->request->post['ebay_profile_id'], $this->request->post);

			$this->response->redirect($this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->form($data);
	}

	public function form($data) {
		$this->load->model('extension/openbay/ebay');
		$this->load->model('extension/openbay/ebay_template');

		$data['user_token']                       = $this->session->data['user_token'];
		$data['shipping_international_zones']     = $this->model_extension_openbay_ebay->getShippingLocations();
		$data['templates']                        = $this->model_extension_openbay_ebay_template->getAll();
		$data['types']                            = $this->model_extension_openbay_ebay_profile->getTypes();

		$setting                                  = array();
		$setting['returns']                       = $this->openbay->ebay->getSetting('returns');
		$setting['dispatch_times']                = $this->openbay->ebay->getSetting('dispatch_time_max');
		$setting['countries']                     = $this->openbay->ebay->getSetting('countries');
		$setting['shipping_types'] 				  = $this->openbay->ebay->getSetting('shipping_types');
		$setting['listing_restrictions'] 		  = $this->openbay->ebay->getSetting('listing_restrictions');

		if (empty($setting['dispatch_times']) || empty($setting['countries']) || empty($setting['returns'])){
			$this->session->data['warning'] = $this->language->get('error_missing_settings');
			$this->response->redirect($this->url->link('extension/openbay/ebay/syncronise', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (is_array($setting['dispatch_times'])) {
			ksort($setting['dispatch_times']);
		}
		if (is_array($setting['countries'])) {
			ksort($setting['countries']);
		}

		$data['setting'] = $setting;

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$profile_info = array();
		if (isset($this->request->get['ebay_profile_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$profile_info = $this->model_extension_openbay_ebay_profile->get($this->request->get['ebay_profile_id']);
			$data['text_manage'] = $this->language->get('text_edit');
			$data['action'] = $this->url->link('extension/openbay/ebay_profile/edit', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/openbay/ebay_profile/add', 'user_token=' . $this->session->data['user_token'], true);
			$data['text_manage'] = $this->language->get('text_add');
		}

		$data['cancel'] = $this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['type'])) {
			$type = $this->request->post['type'];
		} else {
			$type = $profile_info['type'];
		}

		if (!array_key_exists($type, $data['types'])) {
			$this->session->data['error'] = $data['error_no_template'];

			$this->response->redirect($this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true));
		}

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
			'href' => $this->url->link('extension/openbay/ebay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_ebay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/ebay_profile', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title')
		);

		if (isset($this->request->post['default'])) {
			$data['default'] = $this->request->post['default'];
		} elseif (!empty($profile_info)) {
			$data['default'] = $profile_info['default'];
		} else {
			$data['default'] = 0;
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($profile_info)) {
			$data['name'] = $profile_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($profile_info)) {
			$data['description'] = $profile_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} else {
			$data['type'] = $profile_info['type'];
		}

		if (isset($this->request->get['ebay_profile_id'])) {
			$data['ebay_profile_id'] = $this->request->get['ebay_profile_id'];
		} else {
			$data['ebay_profile_id'] = '';
		}

		if (isset($this->request->post['data'])) {
			$data['data'] = $this->request->post['data'];
		} elseif (!empty($profile_info)) {
			$data['data'] = $profile_info['data'];
		} else {
			$data['data'] = array();
		}

		if ($type == 0) {
			$data['zones'] = $this->model_extension_openbay_ebay->getShippingLocations();

			$data['data']['national']['calculated']['types'] = $this->model_extension_openbay_ebay->getShippingService(0, 'calculated');
			$data['data']['national']['flat']['types'] = $this->model_extension_openbay_ebay->getShippingService(0, 'flat');
			$data['data']['international']['calculated']['types'] = $this->model_extension_openbay_ebay->getShippingService(1, 'calculated');
			$data['data']['international']['flat']['types'] = $this->model_extension_openbay_ebay->getShippingService(1, 'flat');

			$data['data']['national']['calculated']['count'] = isset($data['data']['national']['calculated']['service_id']) ? max(array_keys($data['data']['national']['calculated']['service_id']))+1 : 0;
			$data['data']['national']['flat']['count'] = isset($data['data']['national']['flat']['service_id']) ? max(array_keys($data['data']['national']['flat']['service_id']))+1 : 0;
			$data['data']['international']['calculated']['count'] = isset($data['data']['international']['calculated']['service_id']) ? max(array_keys($data['data']['international']['calculated']['service_id']))+1 : 0;
			$data['data']['international']['flat']['count']	= isset($data['data']['international']['flat']['service_id']) ? max(array_keys($data['data']['international']['flat']['service_id']))+1 : 0;

			$payment_types = $this->model_extension_openbay_ebay->getPaymentTypes();
			$data['cod_surcharge'] = 0;

			foreach($payment_types as $payment) {
				if ($payment['ebay_name'] == 'COD') {
					$data['cod_surcharge'] = 1;
				}
			}

			if (!isset($data['data']['national']['shipping_type'])) {
				$data['data']['national']['shipping_type'] = 'flat';
			}

			if (!isset($data['data']['international']['shipping_type'])) {
				$data['data']['international']['shipping_type'] = 'flat';
			}

			$data['html_national_flat']         		= $this->load->view('extension/openbay/ebay_profile_shipping_national_flat', $data);
			$data['html_international_flat']         	= $this->load->view('extension/openbay/ebay_profile_shipping_international_flat', $data);
			$data['html_national_calculated']         	= $this->load->view('extension/openbay/ebay_profile_shipping_national_calculated', $data);
			$data['html_international_calculated']		= $this->load->view('extension/openbay/ebay_profile_shipping_international_calculated', $data);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($data['types'][$type]['template'], $data));
	}

	public function get() {
		$this->load->model('extension/openbay/ebay_profile');
		$this->load->model('extension/openbay/ebay');
		$this->load->language('extension/openbay/ebay_profile');

		$profile_info = $this->model_extension_openbay_ebay_profile->get($this->request->get['ebay_profile_id']);
		$data = array();

		if ($profile_info['type'] == 0) {
			$data['data'] = $profile_info['data'];
			$data['data']['national']['calculated']['types'] = $this->model_extension_openbay_ebay->getShippingService(0, 'calculated');
			$data['data']['international']['calculated']['types'] = $this->model_extension_openbay_ebay->getShippingService(1, 'calculated');
			$data['data']['national']['flat']['types'] = $this->model_extension_openbay_ebay->getShippingService(0, 'flat');
			$data['data']['international']['flat']['types'] = $this->model_extension_openbay_ebay->getShippingService(1, 'flat');

			$data['data']['national']['calculated']['count']	= isset($data['data']['national']['calculated']['service_id']) ? max(array_keys($data['data']['national']['calculated']['service_id']))+1 : 0;
			$data['data']['national']['flat']['count']	= isset($data['data']['national']['flat']['service_id']) ? max(array_keys($data['data']['national']['flat']['service_id']))+1 : 0;
			$data['data']['international']['calculated']['count']	= isset($data['data']['international']['calculated']['service_id']) ? max(array_keys($data['data']['international']['calculated']['service_id']))+1 : 0;
			$data['data']['international']['flat']['count']	= isset($data['data']['international']['flat']['service_id']) ? max(array_keys($data['data']['international']['flat']['service_id']))+1 : 0;

			$data['zones'] = $this->model_extension_openbay_ebay->getShippingLocations();

			$data['text_shipping_service'] = $this->language->get('text_shipping_service');
			$data['text_shipping_first'] = $this->language->get('text_shipping_first');
			$data['button_delete'] = $this->language->get('button_delete');
			$data['text_shipping_zones'] = $this->language->get('text_shipping_zones');
			$data['text_shipping_worldwide'] = $this->language->get('text_shipping_worldwide');
			$data['text_shipping_add'] = $this->language->get('text_shipping_add');
			$data['text_cod_surcharge'] = $this->language->get('text_cod_surcharge');

			$payment_types = $this->model_extension_openbay_ebay->getPaymentTypes();
			$data['cod_surcharge'] = 0;

			if (!empty($payment_types)) {
				foreach($payment_types as $payment) {
					if ($payment['ebay_name'] == 'COD') {
						$data['cod_surcharge'] = 1;
					}
				}
			}
			$return['national']['type'] 				= $data['data']['national']['shipping_type'];
			$return['international']['type'] 			= $data['data']['international']['shipping_type'];

			$return['national_flat_count']   			= (int)$data['data']['national']['flat']['count'];
			$return['national_flat']         			= $this->load->view('extension/openbay/ebay_profile_shipping_national_flat', $data);

			$return['international_flat_count']   		= (int)$data['data']['international']['flat']['count'];
			$return['international_flat']         		= $this->load->view('extension/openbay/ebay_profile_shipping_international_flat', $data);

			$return['national_calculated_count']   		= (int)$data['data']['national']['calculated']['count'];
			$return['national_calculated']         		= $this->load->view('extension/openbay/ebay_profile_shipping_national_calculated', $data);

			$return['international_calculated_count']   = (int)$data['data']['international']['flat']['count'];
			$return['international_calculated']         = $this->load->view('extension/openbay/ebay_profile_shipping_international_calculated', $data);

			$profile_info['html']           			= $return;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($profile_info));
	}

	private function profileValidate() {
		if (!$this->user->hasPermission('modify', 'extension/openbay/ebay_profile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['name'] == '') {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}
