<?php
class ControllerExtensionShippingFedex extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/fedex');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_fedex', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping'));
		}

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

		if (isset($this->error['dimension'])) {
			$data['error_dimension'] = $this->error['dimension'];
		} else {
			$data['error_dimension'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/fedex', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/shipping/fedex', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping');

		if (isset($this->request->post['shipping_fedex_key'])) {
			$data['shipping_fedex_key'] = $this->request->post['shipping_fedex_key'];
		} else {
			$data['shipping_fedex_key'] = $this->config->get('shipping_fedex_key');
		}

		if (isset($this->request->post['shipping_fedex_password'])) {
			$data['shipping_fedex_password'] = $this->request->post['shipping_fedex_password'];
		} else {
			$data['shipping_fedex_password'] = $this->config->get('shipping_fedex_password');
		}

		if (isset($this->request->post['shipping_fedex_account'])) {
			$data['shipping_fedex_account'] = $this->request->post['shipping_fedex_account'];
		} else {
			$data['shipping_fedex_account'] = $this->config->get('shipping_fedex_account');
		}

		if (isset($this->request->post['shipping_fedex_meter'])) {
			$data['shipping_fedex_meter'] = $this->request->post['shipping_fedex_meter'];
		} else {
			$data['shipping_fedex_meter'] = $this->config->get('shipping_fedex_meter');
		}

		if (isset($this->request->post['shipping_fedex_postcode'])) {
			$data['shipping_fedex_postcode'] = $this->request->post['shipping_fedex_postcode'];
		} else {
			$data['shipping_fedex_postcode'] = $this->config->get('shipping_fedex_postcode');
		}

		if (isset($this->request->post['shipping_fedex_server'])) {
			$data['shipping_fedex_server'] = $this->request->post['shipping_fedex_server'];
		} else {
			$data['shipping_fedex_server'] = $this->config->get('shipping_fedex_server');
		}

		if (isset($this->request->post['shipping_fedex_test'])) {
			$data['shipping_fedex_test'] = $this->request->post['shipping_fedex_test'];
		} else {
			$data['shipping_fedex_test'] = $this->config->get('shipping_fedex_test');
		}

		if (isset($this->request->post['shipping_fedex_service'])) {
			$data['shipping_fedex_service'] = $this->request->post['shipping_fedex_service'];
		} elseif ($this->config->has('shipping_fedex_service')) {
			$data['shipping_fedex_service'] = $this->config->get('shipping_fedex_service');
		} else {
			$data['shipping_fedex_service'] = array();
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

		if (isset($this->request->post['shipping_fedex_length'])) {
			$data['shipping_fedex_length'] = $this->request->post['shipping_fedex_length'];
		} else {
			$data['shipping_fedex_length'] = $this->config->get('shipping_fedex_length');
		}

		if (isset($this->request->post['shipping_fedex_width'])) {
			$data['shipping_fedex_width'] = $this->request->post['shipping_fedex_width'];
		} else {
			$data['shipping_fedex_width'] = $this->config->get('shipping_fedex_width');
		}

		if (isset($this->request->post['shipping_fedex_height'])) {
			$data['shipping_fedex_height'] = $this->request->post['shipping_fedex_height'];
		} else {
			$data['shipping_fedex_height'] = $this->config->get('shipping_fedex_height');
		}

		if (isset($this->request->post['shipping_fedex_length_class_id'])) {
			$data['shipping_fedex_length_class_id'] = $this->request->post['shipping_fedex_length_class_id'];
		} else {
			$data['shipping_fedex_length_class_id'] = $this->config->get('shipping_fedex_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['shipping_fedex_dropoff_type'])) {
			$data['shipping_fedex_dropoff_type'] = $this->request->post['shipping_fedex_dropoff_type'];
		} else {
			$data['shipping_fedex_dropoff_type'] = $this->config->get('shipping_fedex_dropoff_type');
		}

		if (isset($this->request->post['shipping_fedex_packaging_type'])) {
			$data['shipping_fedex_packaging_type'] = $this->request->post['shipping_fedex_packaging_type'];
		} else {
			$data['shipping_fedex_packaging_type'] = $this->config->get('shipping_fedex_packaging_type');
		}

		if (isset($this->request->post['shipping_fedex_rate_type'])) {
			$data['shipping_fedex_rate_type'] = $this->request->post['shipping_fedex_rate_type'];
		} else {
			$data['shipping_fedex_rate_type'] = $this->config->get('shipping_fedex_rate_type');
		}

		if (isset($this->request->post['shipping_fedex_destination_type'])) {
			$data['shipping_fedex_destination_type'] = $this->request->post['shipping_fedex_destination_type'];
		} else {
			$data['shipping_fedex_destination_type'] = $this->config->get('shipping_fedex_destination_type');
		}

		if (isset($this->request->post['shipping_fedex_display_time'])) {
			$data['shipping_fedex_display_time'] = $this->request->post['shipping_fedex_display_time'];
		} else {
			$data['shipping_fedex_display_time'] = $this->config->get('shipping_fedex_display_time');
		}

		if (isset($this->request->post['shipping_fedex_display_weight'])) {
			$data['shipping_fedex_display_weight'] = $this->request->post['shipping_fedex_display_weight'];
		} else {
			$data['shipping_fedex_display_weight'] = $this->config->get('shipping_fedex_display_weight');
		}

		if (isset($this->request->post['shipping_fedex_weight_class_id'])) {
			$data['shipping_fedex_weight_class_id'] = $this->request->post['shipping_fedex_weight_class_id'];
		} else {
			$data['shipping_fedex_weight_class_id'] = $this->config->get('shipping_fedex_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['shipping_fedex_tax_class_id'])) {
			$data['shipping_fedex_tax_class_id'] = $this->request->post['shipping_fedex_tax_class_id'];
		} else {
			$data['shipping_fedex_tax_class_id'] = $this->config->get('shipping_fedex_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_fedex_geo_zone_id'])) {
			$data['shipping_fedex_geo_zone_id'] = $this->request->post['shipping_fedex_geo_zone_id'];
		} else {
			$data['shipping_fedex_geo_zone_id'] = $this->config->get('shipping_fedex_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_fedex_status'])) {
			$data['shipping_fedex_status'] = $this->request->post['shipping_fedex_status'];
		} else {
			$data['shipping_fedex_status'] = $this->config->get('shipping_fedex_status');
		}

		if (isset($this->request->post['shipping_fedex_sort_order'])) {
			$data['shipping_fedex_sort_order'] = $this->request->post['shipping_fedex_sort_order'];
		} else {
			$data['shipping_fedex_sort_order'] = $this->config->get('shipping_fedex_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/fedex', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/fedex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shipping_fedex_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['shipping_fedex_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['shipping_fedex_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['shipping_fedex_meter']) {
			$this->error['meter'] = $this->language->get('error_meter');
		}

		if (!$this->request->post['shipping_fedex_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if (!$this->request->post['shipping_fedex_length'] || !$this->request->post['shipping_fedex_width'] || !$this->request->post['shipping_fedex_height']) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		return !$this->error;
	}
}
