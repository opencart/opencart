<?php
class ControllerShippingFedex extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/fedex');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fedex', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_regular_pickup'] = $this->language->get('text_regular_pickup');
		$data['text_request_courier'] = $this->language->get('text_request_courier');
		$data['text_drop_box'] = $this->language->get('text_drop_box');
		$data['text_business_service_center'] = $this->language->get('text_business_service_center');
		$data['text_station'] = $this->language->get('text_station');

		$data['text_fedex_envelope'] = $this->language->get('text_fedex_envelope');
		$data['text_fedex_pak'] = $this->language->get('text_fedex_pak');
		$data['text_fedex_box'] = $this->language->get('text_fedex_box');
		$data['text_fedex_tube'] = $this->language->get('text_fedex_tube');
		$data['text_fedex_10kg_box'] = $this->language->get('text_fedex_10kg_box');
		$data['text_fedex_25kg_box'] = $this->language->get('text_fedex_25kg_box');
		$data['text_your_packaging'] = $this->language->get('text_your_packaging');
		$data['text_list_rate'] = $this->language->get('text_list_rate');
		$data['text_account_rate'] = $this->language->get('text_account_rate');

		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_account'] = $this->language->get('entry_account');
		$data['entry_meter'] = $this->language->get('entry_meter');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_service'] = $this->language->get('entry_service');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_dropoff_type'] = $this->language->get('entry_dropoff_type');
		$data['entry_packaging_type'] = $this->language->get('entry_packaging_type');
		$data['entry_rate_type'] = $this->language->get('entry_rate_type');
		$data['entry_display_time'] = $this->language->get('entry_display_time');
		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_display_time'] = $this->language->get('help_display_time');
		$data['help_length_class'] = $this->language->get('help_length_class');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_weight_class'] = $this->language->get('help_weight_class');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = '';
		}

		if (isset($this->error['meter'])) {
			$data['error_meter'] = $this->error['meter'];
		} else {
			$data['error_meter'] = '';
		}

		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}
		
		if (isset($this->error['dimension'])) {
			$data['error_dimension'] = $this->error['dimension'];
		} else {
			$data['error_dimension'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/fedex', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/fedex', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['fedex_key'])) {
			$data['fedex_key'] = $this->request->post['fedex_key'];
		} else {
			$data['fedex_key'] = $this->config->get('fedex_key');
		}

		if (isset($this->request->post['fedex_password'])) {
			$data['fedex_password'] = $this->request->post['fedex_password'];
		} else {
			$data['fedex_password'] = $this->config->get('fedex_password');
		}

		if (isset($this->request->post['fedex_account'])) {
			$data['fedex_account'] = $this->request->post['fedex_account'];
		} else {
			$data['fedex_account'] = $this->config->get('fedex_account');
		}

		if (isset($this->request->post['fedex_meter'])) {
			$data['fedex_meter'] = $this->request->post['fedex_meter'];
		} else {
			$data['fedex_meter'] = $this->config->get('fedex_meter');
		}

		if (isset($this->request->post['fedex_postcode'])) {
			$data['fedex_postcode'] = $this->request->post['fedex_postcode'];
		} else {
			$data['fedex_postcode'] = $this->config->get('fedex_postcode');
		}

		if (isset($this->request->post['fedex_test'])) {
			$data['fedex_test'] = $this->request->post['fedex_test'];
		} else {
			$data['fedex_test'] = $this->config->get('fedex_test');
		}

		if (isset($this->request->post['fedex_service'])) {
			$data['fedex_service'] = $this->request->post['fedex_service'];
		} elseif ($this->config->has('fedex_service')) {
			$data['fedex_service'] = $this->config->get('fedex_service');
		} else {
			$data['fedex_service'] = array();
		}

		$data['services'] = array();

		$data['services'][] = array(
			'text'  => $this->language->get('text_europe_first_international_priority'),
			'value' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_1_day_freight'),
			'value' => 'FEDEX_1_DAY_FREIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_2_day'),
			'value' => 'FEDEX_2_DAY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_2_day_am'),
			'value' => 'FEDEX_2_DAY_AM'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_2_day_freight'),
			'value' => 'FEDEX_2_DAY_FREIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_3_day_freight'),
			'value' => 'FEDEX_3_DAY_FREIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_express_saver'),
			'value' => 'FEDEX_EXPRESS_SAVER'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_first_freight'),
			'value' => 'FEDEX_FIRST_FREIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_freight_economy'),
			'value' => 'FEDEX_FREIGHT_ECONOMY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_freight_priority'),
			'value' => 'FEDEX_FREIGHT_PRIORITY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_fedex_ground'),
			'value' => 'FEDEX_GROUND'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_first_overnight'),
			'value' => 'FIRST_OVERNIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_ground_home_delivery'),
			'value' => 'GROUND_HOME_DELIVERY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_international_economy'),
			'value' => 'INTERNATIONAL_ECONOMY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_international_economy_freight'),
			'value' => 'INTERNATIONAL_ECONOMY_FREIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_international_first'),
			'value' => 'INTERNATIONAL_FIRST'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_international_priority'),
			'value' => 'INTERNATIONAL_PRIORITY'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_international_priority_freight'),
			'value' => 'INTERNATIONAL_PRIORITY_FREIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_priority_overnight'),
			'value' => 'PRIORITY_OVERNIGHT'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_smart_post'),
			'value' => 'SMART_POST'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_standard_overnight'),
			'value' => 'STANDARD_OVERNIGHT'
		);
		
		
		if (isset($this->request->post['fedex_length'])) {
			$data['fedex_length'] = $this->request->post['fedex_length'];
		} else {
			$data['fedex_length'] = $this->config->get('fedex_length');
		}
		
		if (isset($this->request->post['fedex_width'])) {
			$data['fedex_width'] = $this->request->post['fedex_width'];
		} else {
			$data['fedex_width'] = $this->config->get('fedex_width');
		}
		
		if (isset($this->request->post['fedex_height'])) {
			$data['fedex_height'] = $this->request->post['fedex_height'];
		} else {
			$data['fedex_height'] = $this->config->get('fedex_height');
		}
		
		if (isset($this->request->post['fedex_length_class_id'])) {
			$data['fedex_length_class_id'] = $this->request->post['fedex_length_class_id'];
		} else {
			$data['fedex_length_class_id'] = $this->config->get('fedex_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		
		if (isset($this->request->post['fedex_dropoff_type'])) {
			$data['fedex_dropoff_type'] = $this->request->post['fedex_dropoff_type'];
		} else {
			$data['fedex_dropoff_type'] = $this->config->get('fedex_dropoff_type');
		}

		if (isset($this->request->post['fedex_packaging_type'])) {
			$data['fedex_packaging_type'] = $this->request->post['fedex_packaging_type'];
		} else {
			$data['fedex_packaging_type'] = $this->config->get('fedex_packaging_type');
		}

		if (isset($this->request->post['fedex_rate_type'])) {
			$data['fedex_rate_type'] = $this->request->post['fedex_rate_type'];
		} else {
			$data['fedex_rate_type'] = $this->config->get('fedex_rate_type');
		}

		if (isset($this->request->post['fedex_destination_type'])) {
			$data['fedex_destination_type'] = $this->request->post['fedex_destination_type'];
		} else {
			$data['fedex_destination_type'] = $this->config->get('fedex_destination_type');
		}

		if (isset($this->request->post['fedex_display_time'])) {
			$data['fedex_display_time'] = $this->request->post['fedex_display_time'];
		} else {
			$data['fedex_display_time'] = $this->config->get('fedex_display_time');
		}

		if (isset($this->request->post['fedex_display_weight'])) {
			$data['fedex_display_weight'] = $this->request->post['fedex_display_weight'];
		} else {
			$data['fedex_display_weight'] = $this->config->get('fedex_display_weight');
		}

		if (isset($this->request->post['fedex_weight_class_id'])) {
			$data['fedex_weight_class_id'] = $this->request->post['fedex_weight_class_id'];
		} else {
			$data['fedex_weight_class_id'] = $this->config->get('fedex_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['fedex_tax_class_id'])) {
			$data['fedex_tax_class_id'] = $this->request->post['fedex_tax_class_id'];
		} else {
			$data['fedex_tax_class_id'] = $this->config->get('fedex_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['fedex_geo_zone_id'])) {
			$data['fedex_geo_zone_id'] = $this->request->post['fedex_geo_zone_id'];
		} else {
			$data['fedex_geo_zone_id'] = $this->config->get('fedex_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['fedex_status'])) {
			$data['fedex_status'] = $this->request->post['fedex_status'];
		} else {
			$data['fedex_status'] = $this->config->get('fedex_status');
		}

		if (isset($this->request->post['fedex_sort_order'])) {
			$data['fedex_sort_order'] = $this->request->post['fedex_sort_order'];
		} else {
			$data['fedex_sort_order'] = $this->config->get('fedex_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/fedex.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/fedex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['fedex_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['fedex_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['fedex_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['fedex_meter']) {
			$this->error['meter'] = $this->language->get('error_meter');
		}

		if (!$this->request->post['fedex_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
		if (!$this->request->post['fedex_length'] || !$this->request->post['fedex_width'] || !$this->request->post['fedex_width']) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}		

		return !$this->error;
	}
}