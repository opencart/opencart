<?php
class ControllerShippingUsps extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/usps');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('usps', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_domestic_00'] = $this->language->get('text_domestic_00');
		$data['text_domestic_01'] = $this->language->get('text_domestic_01');
		$data['text_domestic_02'] = $this->language->get('text_domestic_02');
		$data['text_domestic_03'] = $this->language->get('text_domestic_03');
		$data['text_domestic_1'] = $this->language->get('text_domestic_1');
		$data['text_domestic_2'] = $this->language->get('text_domestic_2');
		$data['text_domestic_3'] = $this->language->get('text_domestic_3');
		$data['text_domestic_4'] = $this->language->get('text_domestic_4');
		$data['text_domestic_5'] = $this->language->get('text_domestic_5');
		$data['text_domestic_6'] = $this->language->get('text_domestic_6');
		$data['text_domestic_7'] = $this->language->get('text_domestic_7');
		$data['text_domestic_12'] = $this->language->get('text_domestic_12');
		$data['text_domestic_13'] = $this->language->get('text_domestic_13');
		$data['text_domestic_16'] = $this->language->get('text_domestic_16');
		$data['text_domestic_17'] = $this->language->get('text_domestic_17');
		$data['text_domestic_18'] = $this->language->get('text_domestic_18');
		$data['text_domestic_19'] = $this->language->get('text_domestic_19');
		$data['text_domestic_22'] = $this->language->get('text_domestic_22');
		$data['text_domestic_23'] = $this->language->get('text_domestic_23');
		$data['text_domestic_25'] = $this->language->get('text_domestic_25');
		$data['text_domestic_27'] = $this->language->get('text_domestic_27');
		$data['text_domestic_28'] = $this->language->get('text_domestic_28');
		$data['text_international_1'] = $this->language->get('text_international_1');
		$data['text_international_2'] = $this->language->get('text_international_2');
		$data['text_international_4'] = $this->language->get('text_international_4');
		$data['text_international_5'] = $this->language->get('text_international_5');
		$data['text_international_6'] = $this->language->get('text_international_6');
		$data['text_international_7'] = $this->language->get('text_international_7');
		$data['text_international_8'] = $this->language->get('text_international_8');
		$data['text_international_9'] = $this->language->get('text_international_9');
		$data['text_international_10'] = $this->language->get('text_international_10');
		$data['text_international_11'] = $this->language->get('text_international_11');
		$data['text_international_12'] = $this->language->get('text_international_12');
		$data['text_international_13'] = $this->language->get('text_international_13');
		$data['text_international_14'] = $this->language->get('text_international_14');
		$data['text_international_15'] = $this->language->get('text_international_15');
		$data['text_international_16'] = $this->language->get('text_international_16');
		$data['text_international_21'] = $this->language->get('text_international_21');

		$data['entry_user_id'] = $this->language->get('entry_user_id');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_domestic'] = $this->language->get('entry_domestic');
		$data['entry_international'] = $this->language->get('entry_international');
		$data['entry_size'] = $this->language->get('entry_size');
		$data['entry_container'] = $this->language->get('entry_container');
		$data['entry_machinable'] = $this->language->get('entry_machinable');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_display_time'] = $this->language->get('entry_display_time');
		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug'] = $this->language->get('entry_debug');

		$data['help_dimension'] = $this->language->get('help_dimension');
		$data['help_display_time'] = $this->language->get('help_display_time');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_weight_class'] = $this->language->get('help_weight_class');
		$data['help_debug'] = $this->language->get('help_debug');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/usps', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/usps', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['usps_user_id'])) {
			$data['usps_user_id'] = $this->request->post['usps_user_id'];
		} else {
			$data['usps_user_id'] = $this->config->get('usps_user_id');
		}

		if (isset($this->request->post['usps_postcode'])) {
			$data['usps_postcode'] = $this->request->post['usps_postcode'];
		} else {
			$data['usps_postcode'] = $this->config->get('usps_postcode');
		}

		if (isset($this->request->post['usps_domestic_00'])) {
			$data['usps_domestic_00'] = $this->request->post['usps_domestic_00'];
		} else {
			$data['usps_domestic_00'] = $this->config->get('usps_domestic_00');
		}

		if (isset($this->request->post['usps_domestic_01'])) {
			$data['usps_domestic_01'] = $this->request->post['usps_domestic_01'];
		} else {
			$data['usps_domestic_01'] = $this->config->get('usps_domestic_01');
		}

		if (isset($this->request->post['usps_domestic_02'])) {
			$data['usps_domestic_02'] = $this->request->post['usps_domestic_02'];
		} else {
			$data['usps_domestic_02'] = $this->config->get('usps_domestic_02');
		}

		if (isset($this->request->post['usps_domestic_03'])) {
			$data['usps_domestic_03'] = $this->request->post['usps_domestic_03'];
		} else {
			$data['usps_domestic_03'] = $this->config->get('usps_domestic_03');
		}

		if (isset($this->request->post['usps_domestic_1'])) {
			$data['usps_domestic_1'] = $this->request->post['usps_domestic_1'];
		} else {
			$data['usps_domestic_1'] = $this->config->get('usps_domestic_1');
		}

		if (isset($this->request->post['usps_domestic_2'])) {
			$data['usps_domestic_2'] = $this->request->post['usps_domestic_2'];
		} else {
			$data['usps_domestic_2'] = $this->config->get('usps_domestic_2');
		}

		if (isset($this->request->post['usps_domestic_3'])) {
			$data['usps_domestic_3'] = $this->request->post['usps_domestic_3'];
		} else {
			$data['usps_domestic_3'] = $this->config->get('usps_domestic_3');
		}

		if (isset($this->request->post['usps_domestic_4'])) {
			$data['usps_domestic_4'] = $this->request->post['usps_domestic_4'];
		} else {
			$data['usps_domestic_4'] = $this->config->get('usps_domestic_4');
		}

		if (isset($this->request->post['usps_domestic_5'])) {
			$data['usps_domestic_5'] = $this->request->post['usps_domestic_5'];
		} else {
			$data['usps_domestic_5'] = $this->config->get('usps_domestic_5');
		}

		if (isset($this->request->post['usps_domestic_6'])) {
			$data['usps_domestic_6'] = $this->request->post['usps_domestic_6'];
		} else {
			$data['usps_domestic_6'] = $this->config->get('usps_domestic_6');
		}

		if (isset($this->request->post['usps_domestic_7'])) {
			$data['usps_domestic_7'] = $this->request->post['usps_domestic_7'];
		} else {
			$data['usps_domestic_7'] = $this->config->get('usps_domestic_7');
		}

		if (isset($this->request->post['usps_domestic_12'])) {
			$data['usps_domestic_12'] = $this->request->post['usps_domestic_12'];
		} else {
			$data['usps_domestic_12'] = $this->config->get('usps_domestic_12');
		}

		if (isset($this->request->post['usps_domestic_13'])) {
			$data['usps_domestic_13'] = $this->request->post['usps_domestic_13'];
		} else {
			$data['usps_domestic_13'] = $this->config->get('usps_domestic_13');
		}

		if (isset($this->request->post['usps_domestic_16'])) {
			$data['usps_domestic_16'] = $this->request->post['usps_domestic_16'];
		} else {
			$data['usps_domestic_16'] = $this->config->get('usps_domestic_16');
		}

		if (isset($this->request->post['usps_domestic_17'])) {
			$data['usps_domestic_17'] = $this->request->post['usps_domestic_17'];
		} else {
			$data['usps_domestic_17'] = $this->config->get('usps_domestic_17');
		}

		if (isset($this->request->post['usps_domestic_18'])) {
			$data['usps_domestic_18'] = $this->request->post['usps_domestic_18'];
		} else {
			$data['usps_domestic_18'] = $this->config->get('usps_domestic_18');
		}

		if (isset($this->request->post['usps_domestic_19'])) {
			$data['usps_domestic_19'] = $this->request->post['usps_domestic_19'];
		} else {
			$data['usps_domestic_19'] = $this->config->get('usps_domestic_19');
		}

		if (isset($this->request->post['usps_domestic_22'])) {
			$data['usps_domestic_22'] = $this->request->post['usps_domestic_22'];
		} else {
			$data['usps_domestic_22'] = $this->config->get('usps_domestic_22');
		}

		if (isset($this->request->post['usps_domestic_23'])) {
			$data['usps_domestic_23'] = $this->request->post['usps_domestic_23'];
		} else {
			$data['usps_domestic_23'] = $this->config->get('usps_domestic_23');
		}

		if (isset($this->request->post['usps_domestic_25'])) {
			$data['usps_domestic_25'] = $this->request->post['usps_domestic_25'];
		} else {
			$data['usps_domestic_25'] = $this->config->get('usps_domestic_25');
		}

		if (isset($this->request->post['usps_domestic_27'])) {
			$data['usps_domestic_27'] = $this->request->post['usps_domestic_27'];
		} else {
			$data['usps_domestic_27'] = $this->config->get('usps_domestic_27');
		}

		if (isset($this->request->post['usps_domestic_28'])) {
			$data['usps_domestic_28'] = $this->request->post['usps_domestic_28'];
		} else {
			$data['usps_domestic_28'] = $this->config->get('usps_domestic_28');
		}

		if (isset($this->request->post['usps_international_1'])) {
			$data['usps_international_1'] = $this->request->post['usps_international_1'];
		} else {
			$data['usps_international_1'] = $this->config->get('usps_international_1');
		}

		if (isset($this->request->post['usps_international_2'])) {
			$data['usps_international_2'] = $this->request->post['usps_international_2'];
		} else {
			$data['usps_international_2'] = $this->config->get('usps_international_2');
		}

		if (isset($this->request->post['usps_international_4'])) {
			$data['usps_international_4'] = $this->request->post['usps_international_4'];
		} else {
			$data['usps_international_4'] = $this->config->get('usps_international_4');
		}

		if (isset($this->request->post['usps_international_5'])) {
			$data['usps_international_5'] = $this->request->post['usps_international_5'];
		} else {
			$data['usps_international_5'] = $this->config->get('usps_international_5');
		}

		if (isset($this->request->post['usps_international_6'])) {
			$data['usps_international_6'] = $this->request->post['usps_international_6'];
		} else {
			$data['usps_international_6'] = $this->config->get('usps_international_6');
		}

		if (isset($this->request->post['usps_international_7'])) {
			$data['usps_international_7'] = $this->request->post['usps_international_7'];
		} else {
			$data['usps_international_7'] = $this->config->get('usps_international_7');
		}

		if (isset($this->request->post['usps_international_8'])) {
			$data['usps_international_8'] = $this->request->post['usps_international_8'];
		} else {
			$data['usps_international_8'] = $this->config->get('usps_international_8');
		}

		if (isset($this->request->post['usps_international_9'])) {
			$data['usps_international_9'] = $this->request->post['usps_international_9'];
		} else {
			$data['usps_international_9'] = $this->config->get('usps_international_9');
		}

		if (isset($this->request->post['usps_international_10'])) {
			$data['usps_international_10'] = $this->request->post['usps_international_10'];
		} else {
			$data['usps_international_10'] = $this->config->get('usps_international_10');
		}

		if (isset($this->request->post['usps_international_11'])) {
			$data['usps_international_11'] = $this->request->post['usps_international_11'];
		} else {
			$data['usps_international_11'] = $this->config->get('usps_international_11');
		}

		if (isset($this->request->post['usps_international_12'])) {
			$data['usps_international_12'] = $this->request->post['usps_international_12'];
		} else {
			$data['usps_international_12'] = $this->config->get('usps_international_12');
		}

		if (isset($this->request->post['usps_international_13'])) {
			$data['usps_international_13'] = $this->request->post['usps_international_13'];
		} else {
			$data['usps_international_13'] = $this->config->get('usps_international_13');
		}

		if (isset($this->request->post['usps_international_14'])) {
			$data['usps_international_14'] = $this->request->post['usps_international_14'];
		} else {
			$data['usps_international_14'] = $this->config->get('usps_international_14');
		}

		if (isset($this->request->post['usps_international_15'])) {
			$data['usps_international_15'] = $this->request->post['usps_international_15'];
		} else {
			$data['usps_international_15'] = $this->config->get('usps_international_15');
		}

		if (isset($this->request->post['usps_international_16'])) {
			$data['usps_international_16'] = $this->request->post['usps_international_16'];
		} else {
			$data['usps_international_16'] = $this->config->get('usps_international_16');
		}

		if (isset($this->request->post['usps_international_21'])) {
			$data['usps_international_21'] = $this->request->post['usps_international_21'];
		} else {
			$data['usps_international_21'] = $this->config->get('usps_international_21');
		}

		if (isset($this->request->post['usps_size'])) {
			$data['usps_size'] = $this->request->post['usps_size'];
		} else {
			$data['usps_size'] = $this->config->get('usps_size');
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

		if (isset($this->request->post['usps_container'])) {
			$data['usps_container'] = $this->request->post['usps_container'];
		} else {
			$data['usps_container'] = $this->config->get('usps_container');
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

		if (isset($this->request->post['usps_machinable'])) {
			$data['usps_machinable'] = $this->request->post['usps_machinable'];
		} else {
			$data['usps_machinable'] = $this->config->get('usps_machinable');
		}

		if (isset($this->request->post['usps_length'])) {
			$data['usps_length'] = $this->request->post['usps_length'];
		} else {
			$data['usps_length'] = $this->config->get('usps_length');
		}

		if (isset($this->request->post['usps_width'])) {
			$data['usps_width'] = $this->request->post['usps_width'];
		} else {
			$data['usps_width'] = $this->config->get('usps_width');
		}

		if (isset($this->request->post['usps_height'])) {
			$data['usps_height'] = $this->request->post['usps_height'];
		} else {
			$data['usps_height'] = $this->config->get('usps_height');
		}

		if (isset($this->request->post['usps_length'])) {
			$data['usps_length'] = $this->request->post['usps_length'];
		} else {
			$data['usps_length'] = $this->config->get('usps_length');
		}

		if (isset($this->request->post['usps_display_time'])) {
			$data['usps_display_time'] = $this->request->post['usps_display_time'];
		} else {
			$data['usps_display_time'] = $this->config->get('usps_display_time');
		}

		if (isset($this->request->post['usps_display_weight'])) {
			$data['usps_display_weight'] = $this->request->post['usps_display_weight'];
		} else {
			$data['usps_display_weight'] = $this->config->get('usps_display_weight');
		}

		if (isset($this->request->post['usps_weight_class_id'])) {
			$data['usps_weight_class_id'] = $this->request->post['usps_weight_class_id'];
		} else {
			$data['usps_weight_class_id'] = $this->config->get('usps_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['usps_tax_class_id'])) {
			$data['usps_tax_class_id'] = $this->request->post['usps_tax_class_id'];
		} else {
			$data['usps_tax_class_id'] = $this->config->get('usps_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['usps_geo_zone_id'])) {
			$data['usps_geo_zone_id'] = $this->request->post['usps_geo_zone_id'];
		} else {
			$data['usps_geo_zone_id'] = $this->config->get('usps_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['usps_debug'])) {
			$data['usps_debug'] = $this->request->post['usps_debug'];
		} else {
			$data['usps_debug'] = $this->config->get('usps_debug');
		}

		if (isset($this->request->post['usps_status'])) {
			$data['usps_status'] = $this->request->post['usps_status'];
		} else {
			$data['usps_status'] = $this->config->get('usps_status');
		}

		if (isset($this->request->post['usps_sort_order'])) {
			$data['usps_sort_order'] = $this->request->post['usps_sort_order'];
		} else {
			$data['usps_sort_order'] = $this->config->get('usps_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/usps.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/usps')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['usps_user_id']) {
			$this->error['user_id'] = $this->language->get('error_user_id');
		}

		if (!$this->request->post['usps_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if (!$this->request->post['usps_width']) {
			$this->error['dimension'] = $this->language->get('error_width');
		}

		if (!$this->request->post['usps_height']) {
			$this->error['dimension'] = $this->language->get('error_height');
		}

		if (!$this->request->post['usps_length']) {
			$this->error['dimension'] = $this->language->get('error_length');
		}

		return !$this->error;
	}
}