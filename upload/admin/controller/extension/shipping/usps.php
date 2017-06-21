<?php
class ControllerExtensionShippingUsps extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/usps');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_usps', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['user_id'])) {
			$data['error_user_id'] = $this->error['user_id'];
		} else {
			$data['error_user_id'] = '';
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/usps', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/usps', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_usps_user_id'])) {
			$data['shipping_usps_user_id'] = $this->request->post['shipping_usps_user_id'];
		} else {
			$data['shipping_usps_user_id'] = $this->config->get('shipping_usps_user_id');
		}

		if (isset($this->request->post['shipping_usps_postcode'])) {
			$data['shipping_usps_postcode'] = $this->request->post['shipping_usps_postcode'];
		} else {
			$data['shipping_usps_postcode'] = $this->config->get('shipping_usps_postcode');
		}

		if (isset($this->request->post['shipping_usps_domestic_00'])) {
			$data['shipping_usps_domestic_00'] = $this->request->post['shipping_usps_domestic_00'];
		} else {
			$data['shipping_usps_domestic_00'] = $this->config->get('shipping_usps_domestic_00');
		}

		if (isset($this->request->post['shipping_usps_domestic_01'])) {
			$data['shipping_usps_domestic_01'] = $this->request->post['shipping_usps_domestic_01'];
		} else {
			$data['shipping_usps_domestic_01'] = $this->config->get('shipping_usps_domestic_01');
		}

		if (isset($this->request->post['shipping_usps_domestic_02'])) {
			$data['shipping_usps_domestic_02'] = $this->request->post['shipping_usps_domestic_02'];
		} else {
			$data['shipping_usps_domestic_02'] = $this->config->get('shipping_usps_domestic_02');
		}

		if (isset($this->request->post['shipping_usps_domestic_03'])) {
			$data['shipping_usps_domestic_03'] = $this->request->post['shipping_usps_domestic_03'];
		} else {
			$data['shipping_usps_domestic_03'] = $this->config->get('shipping_usps_domestic_03');
		}

		if (isset($this->request->post['shipping_usps_domestic_1'])) {
			$data['shipping_usps_domestic_1'] = $this->request->post['shipping_usps_domestic_1'];
		} else {
			$data['shipping_usps_domestic_1'] = $this->config->get('shipping_usps_domestic_1');
		}

		if (isset($this->request->post['shipping_usps_domestic_2'])) {
			$data['shipping_usps_domestic_2'] = $this->request->post['shipping_usps_domestic_2'];
		} else {
			$data['shipping_usps_domestic_2'] = $this->config->get('shipping_usps_domestic_2');
		}

		if (isset($this->request->post['shipping_usps_domestic_3'])) {
			$data['shipping_usps_domestic_3'] = $this->request->post['shipping_usps_domestic_3'];
		} else {
			$data['shipping_usps_domestic_3'] = $this->config->get('shipping_usps_domestic_3');
		}

		if (isset($this->request->post['shipping_usps_domestic_4'])) {
			$data['shipping_usps_domestic_4'] = $this->request->post['shipping_usps_domestic_4'];
		} else {
			$data['shipping_usps_domestic_4'] = $this->config->get('shipping_usps_domestic_4');
		}

		if (isset($this->request->post['shipping_usps_domestic_5'])) {
			$data['shipping_usps_domestic_5'] = $this->request->post['shipping_usps_domestic_5'];
		} else {
			$data['shipping_usps_domestic_5'] = $this->config->get('shipping_usps_domestic_5');
		}

		if (isset($this->request->post['shipping_usps_domestic_6'])) {
			$data['shipping_usps_domestic_6'] = $this->request->post['shipping_usps_domestic_6'];
		} else {
			$data['shipping_usps_domestic_6'] = $this->config->get('shipping_usps_domestic_6');
		}

		if (isset($this->request->post['shipping_usps_domestic_7'])) {
			$data['shipping_usps_domestic_7'] = $this->request->post['shipping_usps_domestic_7'];
		} else {
			$data['shipping_usps_domestic_7'] = $this->config->get('shipping_usps_domestic_7');
		}

		if (isset($this->request->post['shipping_usps_domestic_12'])) {
			$data['shipping_usps_domestic_12'] = $this->request->post['shipping_usps_domestic_12'];
		} else {
			$data['shipping_usps_domestic_12'] = $this->config->get('shipping_usps_domestic_12');
		}

		if (isset($this->request->post['shipping_usps_domestic_13'])) {
			$data['shipping_usps_domestic_13'] = $this->request->post['shipping_usps_domestic_13'];
		} else {
			$data['shipping_usps_domestic_13'] = $this->config->get('shipping_usps_domestic_13');
		}

		if (isset($this->request->post['shipping_usps_domestic_16'])) {
			$data['shipping_usps_domestic_16'] = $this->request->post['shipping_usps_domestic_16'];
		} else {
			$data['shipping_usps_domestic_16'] = $this->config->get('shipping_usps_domestic_16');
		}

		if (isset($this->request->post['shipping_usps_domestic_17'])) {
			$data['shipping_usps_domestic_17'] = $this->request->post['shipping_usps_domestic_17'];
		} else {
			$data['shipping_usps_domestic_17'] = $this->config->get('shipping_usps_domestic_17');
		}

		if (isset($this->request->post['shipping_usps_domestic_18'])) {
			$data['shipping_usps_domestic_18'] = $this->request->post['shipping_usps_domestic_18'];
		} else {
			$data['shipping_usps_domestic_18'] = $this->config->get('shipping_usps_domestic_18');
		}

		if (isset($this->request->post['shipping_usps_domestic_19'])) {
			$data['shipping_usps_domestic_19'] = $this->request->post['shipping_usps_domestic_19'];
		} else {
			$data['shipping_usps_domestic_19'] = $this->config->get('shipping_usps_domestic_19');
		}

		if (isset($this->request->post['shipping_usps_domestic_22'])) {
			$data['shipping_usps_domestic_22'] = $this->request->post['shipping_usps_domestic_22'];
		} else {
			$data['shipping_usps_domestic_22'] = $this->config->get('shipping_usps_domestic_22');
		}

		if (isset($this->request->post['shipping_usps_domestic_23'])) {
			$data['shipping_usps_domestic_23'] = $this->request->post['shipping_usps_domestic_23'];
		} else {
			$data['shipping_usps_domestic_23'] = $this->config->get('shipping_usps_domestic_23');
		}

		if (isset($this->request->post['shipping_usps_domestic_25'])) {
			$data['shipping_usps_domestic_25'] = $this->request->post['shipping_usps_domestic_25'];
		} else {
			$data['shipping_usps_domestic_25'] = $this->config->get('shipping_usps_domestic_25');
		}

		if (isset($this->request->post['shipping_usps_domestic_27'])) {
			$data['shipping_usps_domestic_27'] = $this->request->post['shipping_usps_domestic_27'];
		} else {
			$data['shipping_usps_domestic_27'] = $this->config->get('shipping_usps_domestic_27');
		}

		if (isset($this->request->post['shipping_usps_domestic_28'])) {
			$data['shipping_usps_domestic_28'] = $this->request->post['shipping_usps_domestic_28'];
		} else {
			$data['shipping_usps_domestic_28'] = $this->config->get('shipping_usps_domestic_28');
		}

		if (isset($this->request->post['shipping_usps_international_1'])) {
			$data['shipping_usps_international_1'] = $this->request->post['shipping_usps_international_1'];
		} else {
			$data['shipping_usps_international_1'] = $this->config->get('shipping_usps_international_1');
		}

		if (isset($this->request->post['shipping_usps_international_2'])) {
			$data['shipping_usps_international_2'] = $this->request->post['shipping_usps_international_2'];
		} else {
			$data['shipping_usps_international_2'] = $this->config->get('shipping_usps_international_2');
		}

		if (isset($this->request->post['shipping_usps_international_4'])) {
			$data['shipping_usps_international_4'] = $this->request->post['shipping_usps_international_4'];
		} else {
			$data['shipping_usps_international_4'] = $this->config->get('shipping_usps_international_4');
		}

		if (isset($this->request->post['shipping_usps_international_5'])) {
			$data['shipping_usps_international_5'] = $this->request->post['shipping_usps_international_5'];
		} else {
			$data['shipping_usps_international_5'] = $this->config->get('shipping_usps_international_5');
		}

		if (isset($this->request->post['shipping_usps_international_6'])) {
			$data['shipping_usps_international_6'] = $this->request->post['shipping_usps_international_6'];
		} else {
			$data['shipping_usps_international_6'] = $this->config->get('shipping_usps_international_6');
		}

		if (isset($this->request->post['shipping_usps_international_7'])) {
			$data['shipping_usps_international_7'] = $this->request->post['shipping_usps_international_7'];
		} else {
			$data['shipping_usps_international_7'] = $this->config->get('shipping_usps_international_7');
		}

		if (isset($this->request->post['shipping_usps_international_8'])) {
			$data['shipping_usps_international_8'] = $this->request->post['shipping_usps_international_8'];
		} else {
			$data['shipping_usps_international_8'] = $this->config->get('shipping_usps_international_8');
		}

		if (isset($this->request->post['shipping_usps_international_9'])) {
			$data['shipping_usps_international_9'] = $this->request->post['shipping_usps_international_9'];
		} else {
			$data['shipping_usps_international_9'] = $this->config->get('shipping_usps_international_9');
		}

		if (isset($this->request->post['shipping_usps_international_10'])) {
			$data['shipping_usps_international_10'] = $this->request->post['shipping_usps_international_10'];
		} else {
			$data['shipping_usps_international_10'] = $this->config->get('shipping_usps_international_10');
		}

		if (isset($this->request->post['shipping_usps_international_11'])) {
			$data['shipping_usps_international_11'] = $this->request->post['shipping_usps_international_11'];
		} else {
			$data['shipping_usps_international_11'] = $this->config->get('shipping_usps_international_11');
		}

		if (isset($this->request->post['shipping_usps_international_12'])) {
			$data['shipping_usps_international_12'] = $this->request->post['shipping_usps_international_12'];
		} else {
			$data['shipping_usps_international_12'] = $this->config->get('shipping_usps_international_12');
		}

		if (isset($this->request->post['shipping_usps_international_13'])) {
			$data['shipping_usps_international_13'] = $this->request->post['shipping_usps_international_13'];
		} else {
			$data['shipping_usps_international_13'] = $this->config->get('shipping_usps_international_13');
		}

		if (isset($this->request->post['shipping_usps_international_14'])) {
			$data['shipping_usps_international_14'] = $this->request->post['shipping_usps_international_14'];
		} else {
			$data['shipping_usps_international_14'] = $this->config->get('shipping_usps_international_14');
		}

		if (isset($this->request->post['shipping_usps_international_15'])) {
			$data['shipping_usps_international_15'] = $this->request->post['shipping_usps_international_15'];
		} else {
			$data['shipping_usps_international_15'] = $this->config->get('shipping_usps_international_15');
		}

		if (isset($this->request->post['shipping_usps_international_16'])) {
			$data['shipping_usps_international_16'] = $this->request->post['shipping_usps_international_16'];
		} else {
			$data['shipping_usps_international_16'] = $this->config->get('shipping_usps_international_16');
		}

		if (isset($this->request->post['shipping_usps_international_21'])) {
			$data['shipping_usps_international_21'] = $this->request->post['shipping_usps_international_21'];
		} else {
			$data['shipping_usps_international_21'] = $this->config->get('shipping_usps_international_21');
		}

		if (isset($this->request->post['shipping_usps_size'])) {
			$data['shipping_usps_size'] = $this->request->post['shipping_usps_size'];
		} else {
			$data['shipping_usps_size'] = $this->config->get('shipping_usps_size');
		}

		$data['sizes'] = array();

		$data['sizes'][] = array(
			'text'  => $this->language->get('text_regular'),
			'value' => 'REGULAR'
		);

		$data['sizes'][] = array(
			'text'  => $this->language->get('text_large'),
			'value' => 'LARGE'
		);

		if (isset($this->request->post['shipping_usps_container'])) {
			$data['shipping_usps_container'] = $this->request->post['shipping_usps_container'];
		} else {
			$data['shipping_usps_container'] = $this->config->get('shipping_usps_container');
		}

		$data['containers'] = array();

		$data['containers'][] = array(
			'text'  => $this->language->get('text_rectangular'),
			'value' => 'RECTANGULAR'
		);

		$data['containers'][] = array(
			'text'  => $this->language->get('text_non_rectangular'),
			'value' => 'NONRECTANGULAR'
		);

		$data['containers'][] = array(
			'text'  => $this->language->get('text_variable'),
			'value' => 'VARIABLE'
		);

		if (isset($this->request->post['shipping_usps_machinable'])) {
			$data['shipping_usps_machinable'] = $this->request->post['shipping_usps_machinable'];
		} else {
			$data['shipping_usps_machinable'] = $this->config->get('shipping_usps_machinable');
		}

		if (isset($this->request->post['shipping_usps_length'])) {
			$data['shipping_usps_length'] = $this->request->post['shipping_usps_length'];
		} else {
			$data['shipping_usps_length'] = $this->config->get('shipping_usps_length');
		}

		if (isset($this->request->post['shipping_usps_width'])) {
			$data['shipping_usps_width'] = $this->request->post['shipping_usps_width'];
		} else {
			$data['shipping_usps_width'] = $this->config->get('shipping_usps_width');
		}

		if (isset($this->request->post['shipping_usps_height'])) {
			$data['shipping_usps_height'] = $this->request->post['shipping_usps_height'];
		} else {
			$data['shipping_usps_height'] = $this->config->get('shipping_usps_height');
		}

		if (isset($this->request->post['shipping_usps_display_time'])) {
			$data['shipping_usps_display_time'] = $this->request->post['shipping_usps_display_time'];
		} else {
			$data['shipping_usps_display_time'] = $this->config->get('shipping_usps_display_time');
		}

		if (isset($this->request->post['shipping_usps_display_weight'])) {
			$data['shipping_usps_display_weight'] = $this->request->post['shipping_usps_display_weight'];
		} else {
			$data['shipping_usps_display_weight'] = $this->config->get('shipping_usps_display_weight');
		}

		if (isset($this->request->post['shipping_usps_weight_class_id'])) {
			$data['shipping_usps_weight_class_id'] = $this->request->post['shipping_usps_weight_class_id'];
		} else {
			$data['shipping_usps_weight_class_id'] = $this->config->get('shipping_usps_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['shipping_usps_tax_class_id'])) {
			$data['shipping_usps_tax_class_id'] = $this->request->post['shipping_usps_tax_class_id'];
		} else {
			$data['shipping_usps_tax_class_id'] = $this->config->get('shipping_usps_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_usps_geo_zone_id'])) {
			$data['shipping_usps_geo_zone_id'] = $this->request->post['shipping_usps_geo_zone_id'];
		} else {
			$data['shipping_usps_geo_zone_id'] = $this->config->get('shipping_usps_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_usps_debug'])) {
			$data['shipping_usps_debug'] = $this->request->post['shipping_usps_debug'];
		} else {
			$data['shipping_usps_debug'] = $this->config->get('shipping_usps_debug');
		}

		if (isset($this->request->post['shipping_usps_status'])) {
			$data['shipping_usps_status'] = $this->request->post['shipping_usps_status'];
		} else {
			$data['shipping_usps_status'] = $this->config->get('shipping_usps_status');
		}

		if (isset($this->request->post['shipping_usps_sort_order'])) {
			$data['shipping_usps_sort_order'] = $this->request->post['shipping_usps_sort_order'];
		} else {
			$data['shipping_usps_sort_order'] = $this->config->get('shipping_usps_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/usps', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/usps')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shipping_usps_user_id']) {
			$this->error['user_id'] = $this->language->get('error_user_id');
		}

		if (!$this->request->post['shipping_usps_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if (!$this->request->post['shipping_usps_width']) {
			$this->error['dimension'] = $this->language->get('error_width');
		}

		if (!$this->request->post['shipping_usps_height']) {
			$this->error['dimension'] = $this->language->get('error_height');
		}

		if (!$this->request->post['shipping_usps_length']) {
			$this->error['dimension'] = $this->language->get('error_length');
		}

		return !$this->error;
	}
}
