<?php
class ControllerOpenbayEbayProfile extends Controller {
	private $error = array();

	public function profileAll() {
		$data = $this->load->language('openbay/ebay_profile');

		$this->load->model('openbay/ebay_profile');

		$this->document->setTitle($data['text_title_list']);
		$this->document->addScript('view/javascript/openbay/faq.js');

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

		$data['insert']  = $this->url->link('openbay/ebay_profile/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['types']    = $this->model_openbay_ebay_profile->getTypes();
		$data['profiles'] = $this->model_openbay_ebay_profile->getAll();
		$data['token']    = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_ebay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay_profile/profileAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_heading'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/ebay_profile_list.tpl', $data));

	}

	public function add() {
		$data = $this->load->language('openbay/ebay_profile');

		$this->load->model('openbay/ebay_profile');

		$data['page_title']   = $data['text_title_list_add'];
		$data['btn_save']     = $this->url->link('openbay/ebay_profile/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']       = $this->url->link('openbay/ebay_profile/profileAll', 'token=' . $this->session->data['token'], 'SSL');

		if (!isset($this->request->post['step1'])) {
			if ($this->request->post && $this->profileValidate()) {
				$this->session->data['success'] = $data['text_added'];

				$this->model_openbay_ebay_profile->add($this->request->post);

				$this->response->redirect($this->url->link('openbay/ebay_profile/ProfileAll&token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->profileForm($data);
	}

	public function delete() {
		$this->load->model('openbay/ebay_profile');

		if (!$this->user->hasPermission('modify', 'openbay/ebay_profile')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}else{
			if (isset($this->request->get['ebay_profile_id'])) {
				$this->model_openbay_ebay_profile->delete($this->request->get['ebay_profile_id']);
			}
		}

		$this->response->redirect($this->url->link('openbay/ebay_profile/profileAll&token=' . $this->session->data['token'], 'SSL'));
	}

	public function edit() {
		$data = $this->load->language('openbay/ebay_profile');

		$this->load->model('openbay/ebay_profile');

		$data['page_title']   = $data['text_title_list_edit'];
		$data['btn_save']     = $this->url->link('openbay/ebay_profile/edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']       = $this->url->link('openbay/ebay_profile/profileAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->profileValidate()) {
			$this->session->data['success'] = $data['text_updated'];

			$this->model_openbay_ebay_profile->edit($this->request->post['ebay_profile_id'], $this->request->post);

			$this->response->redirect($this->url->link('openbay/ebay_profile/profileAll&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->profileForm($data);
	}

	public function profileForm($data) {
		$this->load->model('openbay/ebay');
		$this->load->model('openbay/ebay_template');

		$data['token']                            = $this->session->data['token'];
		$data['shipping_international_zones']     = $this->model_openbay_ebay->getShippingLocations();
		$data['templates']                        = $this->model_openbay_ebay_template->getAll();
		$data['types']                            = $this->model_openbay_ebay_profile->getTypes();

		$setting                                  = array();
		$setting['dispatch_times']                = $this->openbay->ebay->getSetting('dispatch_time_max');
		$setting['countries']                     = $this->openbay->ebay->getSetting('countries');
		$setting['returns']                       = $this->openbay->ebay->getSetting('returns');

		if(empty($setting['dispatch_times']) || empty($setting['countries']) || empty($setting['returns'])){
			$this->session->data['warning'] = $this->language->get('text_error_missing_settings');
			$this->response->redirect($this->url->link('openbay/openbay/viewSync&token=' . $this->session->data['token'], 'SSL'));
		}

		if(is_array($setting['dispatch_times'])){ ksort($setting['dispatch_times']); }
		if(is_array($setting['countries'])){ ksort($setting['countries']); }

		$data['setting']                          = $setting;

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$profile_info = array();
		if (isset($this->request->get['ebay_profile_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$profile_info = $this->model_openbay_ebay_profile->get($this->request->get['ebay_profile_id']);
		}

		if (isset($this->request->post['type'])) {
			$type = $this->request->post['type'];
		} else {
			$type = $profile_info['type'];
		}

		if (!array_key_exists($type, $data['types'])) {
			$this->session->data['error'] = $data['text_no_template'];

			$this->response->redirect($this->url->link('openbay/ebay_profile/profileAll&token=' . $this->session->data['token']));
		}

		$this->document->addScript('view/javascript/openbay/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'OpenBay Pro',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'eBay',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay_profile/profileAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'Profiles',
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
			$data['data'] = '';
		}

		if ($type == 0) {

			$i = 0;
			$j = 0;

			$national = array();
			$international = array();

			if (isset($profile_info['data'])) {
				if (isset($profile_info['data']['service_national']) && is_array($profile_info['data']['service_national'])) {
					foreach ($profile_info['data']['service_national'] as $key => $service) {
						$national[] = array(
							'id' => $service,
							'price' => $profile_info['data']['price_national'][$key],
							'additional' => $profile_info['data']['priceadditional_national'][$key],
							'name' => $this->model_openbay_ebay->getShippingServiceName('0', $service)
						);
						$i++;
					}
				}

				if (isset($profile_info['data']['service_international']) && is_array($profile_info['data']['service_international'])) {
					foreach ($profile_info['data']['service_international'] as $key => $service) {

						if(!isset($profile_info['data']['shipto_international'][$key])){
							$profile_info['data']['shipto_international'][$key] = array();
						}

						$international[] = array(
							'id'            => $service,
							'price'         => $profile_info['data']['price_international'][$key],
							'additional'    => $profile_info['data']['priceadditional_international'][$key],
							'name'          => $this->model_openbay_ebay->getShippingServiceName('1', $service),
							'shipto'        => $profile_info['data']['shipto_international'][$key]
						);
						$j++;
					}
				}
			}

			$data['data']['shipping_national']           = $national;
			$data['data']['shipping_national_count']     = $i;
			$data['data']['shipping_international']      = $international;
			$data['data']['shipping_international_count']= $j;
		}

		$this->document->setTitle($data['page_title']);

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($data['types'][$type]['template'], $data));
	}

	public function profileGet() {
		$this->load->model('openbay/ebay_profile');
		$this->load->model('openbay/ebay');
		$this->load->language('openbay/ebay_profile');

		$profile_info = $this->model_openbay_ebay_profile->get($this->request->get['ebay_profile_id']);
		$zones = $this->model_openbay_ebay->getShippingLocations();

		$national = array();

		$i = 0;
		if (isset($profile_info['data']['service_national']) && is_array($profile_info['data']['service_national'])) {
			foreach ($profile_info['data']['service_national'] as $key => $service) {
				$i++;
				$national[$i] = array(
					'id'            => $service,
					'price'         => $profile_info['data']['price_national'][$key],
					'additional'    => $profile_info['data']['priceadditional_national'][$key],
					'name'          => $this->model_openbay_ebay->getShippingServiceName('0', $service)
				);
			}
		}

		$shipping_national          = $national;
		$shipping_national_count    = $i;
		$international              = array();

		$i = 0;
		if (isset($profile_info['data']['service_international']) && is_array($profile_info['data']['service_international'])) {
			foreach ($profile_info['data']['service_international'] as $key => $service) {
				$i++;
				$international[$i] = array(
					'id'            => $service,
					'price'         => $profile_info['data']['price_international'][$key],
					'additional'    => $profile_info['data']['priceadditional_international'][$key],
					'name'          => $this->model_openbay_ebay->getShippingServiceName('1', $service),
					'shipto'        => $profile_info['data']['shipto_international'][$key]
				);
			}
		}

		$shipping_international         = $international;
		$shipping_international_count   = $i;
		$return                         = array();
		$tmp                            = '';

		if(is_array($shipping_national)){
			foreach($shipping_national as $key => $service){
				$shipping_data = array();
				$shipping_data['key'] = $key;
				$shipping_data['service'] = $service;
				$shipping_data['text_shipping_service'] = $this->language->get('text_shipping_service');
				$shipping_data['text_shipping_first'] = $this->language->get('text_shipping_first');
				$shipping_data['text_btn_remove'] = $this->language->get('text_btn_remove');
				$shipping_data['text_shipping_add'] = $this->language->get('text_shipping_add');

				$tmp .= $this->load->view('openbay/ebay_profile_shipping_national.tpl', $shipping_data);
			}
		}

		$return['national_count']   = (int)$shipping_national_count;
		$return['national']         = $tmp;
		$tmp                        = '';

		if(is_array($shipping_international)) {
			foreach($shipping_international as $key => $service){
				$shipping_data = array();
				$shipping_data['key'] = $key;
				$shipping_data['service'] = $service;
				$shipping_data['zones'] = $zones;
				$shipping_data['text_shipping_service'] = $this->language->get('text_shipping_service');
				$shipping_data['text_shipping_first'] = $this->language->get('text_shipping_first');
				$shipping_data['text_btn_remove'] = $this->language->get('text_btn_remove');
				$shipping_data['text_shipping_zones'] = $this->language->get('text_shipping_zones');
				$shipping_data['text_shipping_worldwide'] = $this->language->get('text_shipping_worldwide');
				$shipping_data['text_shipping_add'] = $this->language->get('text_shipping_add');

				$tmp .= $this->load->view('openbay/ebay_profile_shipping_international.tpl', $shipping_data);
			}
		}
		$return['international_count']  = (int)$shipping_international_count;
		$return['international']        = $tmp;
		$profile_info['html']           = $return;

		$this->response->setOutput(json_encode($profile_info));
	}

	private function profileValidate() {
		if (!$this->user->hasPermission('modify', 'openbay/ebay_profile')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}

		if ($this->request->post['name'] == '') {
			$this->error['name'] = $this->language->get('text_error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}