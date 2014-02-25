<?php
class ControllerOpenbayEbayProfile extends Controller {
	private $error = array();

	public function profileAll() {
		$data = $this->load->language('openbay/ebay_profile');

		$this->load->model('openbay/ebay_profile');

		$this->document->setTitle($data['text_title_list']);
		$this->document->addStyle('view/stylesheet/openbay.css');
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
			'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
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

		$this->document->addStyle('view/stylesheet/openbay.css');
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

		if ($type == 0 && isset($profile_info['data'])) {
			$national = array();

			$i = 0;
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

			$data['data']['shipping_national']        = $national;
			$data['data']['shipping_national_count']  = $i;

			$international = array();

			$i = 0;
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
					$i++;
				}
			}

			$data['data']['shipping_international']           = $international;
			$data['data']['shipping_international_count']     = $i;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($data['types'][$type]['template'], $data));
	}

	public function profileGet(){
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
				$tmp .= '<p class="shipping_national_'.$key.'"><label><strong>'.$this->language->get('text_shipping_service').': </strong><label> ';
				$tmp .= '<input type="hidden" name="service_national['.$key.']" value="'.$service['id'].'" />'.$service['name'].'</p><p class="shipping_national_'.$key.'"><label>'.$this->language->get('text_shipping_first').': </label>';
				$tmp .= '<input type="text" name="price_national['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
				$tmp .= '&nbsp;&nbsp;<label>'.$this->language->get('text_shipping_add').': </label>';
				$tmp .= '<input type="text" name="priceadditional_national['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'national\',\''.$key.'\');" class="button"><span>'.$this->language->get('text_btn_remove').'</span></a></p>';
			}
		}
		$return['national_count']   = (int)$shipping_national_count;
		$return['national']         = $tmp;
		$tmp                        = '';

		if(is_array($shipping_international)) {
			foreach($shipping_international as $key => $service){

				$tmp .= '<p class="shipping_international_'.$key.'" style="border-top:1px dotted; margin:0; padding:8px 0;"><label><strong>'.$this->language->get('text_shipping_service').': </strong><label> ';
				$tmp .= '<input type="hidden" name="service_international['.$key.']" value="'.$service['id'].'" />'.$service['name'].'</p>';

				$tmp .= '<h5 style="margin:5px 0;" class="shipping_international_'.$key.'">'.$this->language->get('text_shipping_zones').'</h5>';
				$tmp .= '<div style="border:1px solid #000; background-color:#F5F5F5; width:100%; min-height:40px; margin-bottom:10px; display:inline-block;" class="shipping_international_'.$key.'">';
				$tmp .= '<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
				$tmp .= '<input type="checkbox" name="shipto_international['.$key.'][]" value="Worldwide" ';
				if(in_array('Worldwide', $service['shipto'])){ $tmp .= ' checked="checked"'; }
				$tmp .= '/> '.$this->language->get('text_shipping_worldwide').'</div>';

				foreach($zones as $zone) {
					$tmp .= '<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
					$tmp .= '<input type="checkbox" name="shipto_international['.$key.'][]" value="'. $zone['shipping_location'] . '"';
					if(in_array($zone['shipping_location'], $service['shipto'])){ $tmp .= ' checked="checked"'; }
					$tmp .= '/> '.$zone['description'].'</div>';
				}

				$tmp .= '</div>';
				$tmp .= '<div style="clear:both;" class="shipping_international_'.$key.'"></div>';
				$tmp .= '<p class="shipping_international_'.$key.'"><label>'.$this->language->get('text_shipping_first').': </label>';
				$tmp .= '<input type="text" name="price_international['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
				$tmp .= '&nbsp;&nbsp;<label>'.$this->language->get('text_shipping_add').': </label>';
				$tmp .= '<input type="text" name="priceadditional_international['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'international\',\''.$key.'\');" class="button"><span>'.$this->language->get('text_btn_remove').'</span></a></p>';
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

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}