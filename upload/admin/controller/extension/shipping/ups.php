<?php
class ControllerExtensionShippingUPS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/ups');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_ups', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
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

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}

		if (isset($this->error['state'])) {
			$data['error_state'] = $this->error['state'];
		} else {
			$data['error_state'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
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
			'href' => $this->url->link('extension/shipping/ups', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/ups', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_ups_key'])) {
			$data['shipping_ups_key'] = $this->request->post['shipping_ups_key'];
		} else {
			$data['shipping_ups_key'] = $this->config->get('shipping_ups_key');
		}

		if (isset($this->request->post['shipping_ups_username'])) {
			$data['shipping_ups_username'] = $this->request->post['shipping_ups_username'];
		} else {
			$data['shipping_ups_username'] = $this->config->get('shipping_ups_username');
		}

		if (isset($this->request->post['shipping_ups_password'])) {
			$data['shipping_ups_password'] = $this->request->post['shipping_ups_password'];
		} else {
			$data['shipping_ups_password'] = $this->config->get('shipping_ups_password');
		}

		if (isset($this->request->post['shipping_ups_pickup'])) {
			$data['shipping_ups_pickup'] = $this->request->post['shipping_ups_pickup'];
		} else {
			$data['shipping_ups_pickup'] = $this->config->get('shipping_ups_pickup');
		}

		$data['pickups'] = array();

		$data['pickups'][] = array(
			'value' => '01',
			'text'  => $this->language->get('text_daily_pickup')
		);

		$data['pickups'][] = array(
			'value' => '03',
			'text'  => $this->language->get('text_customer_counter')
		);

		$data['pickups'][] = array(
			'value' => '06',
			'text'  => $this->language->get('text_one_time_pickup')
		);

		$data['pickups'][] = array(
			'value' => '07',
			'text'  => $this->language->get('text_on_call_air_pickup')
		);

		$data['pickups'][] = array(
			'value' => '19',
			'text'  => $this->language->get('text_letter_center')
		);

		$data['pickups'][] = array(
			'value' => '20',
			'text'  => $this->language->get('text_air_service_center')
		);

		$data['pickups'][] = array(
			'value' => '11',
			'text'  => $this->language->get('text_suggested_retail_rates')
		);

		if (isset($this->request->post['shipping_ups_packaging'])) {
			$data['shipping_ups_packaging'] = $this->request->post['shipping_ups_packaging'];
		} else {
			$data['shipping_ups_packaging'] = $this->config->get('shipping_ups_packaging');
		}

		$data['packages'] = array();

		$data['packages'][] = array(
			'value' => '02',
			'text'  => $this->language->get('text_package')
		);

		$data['packages'][] = array(
			'value' => '01',
			'text'  => $this->language->get('text_ups_letter')
		);

		$data['packages'][] = array(
			'value' => '03',
			'text'  => $this->language->get('text_ups_tube')
		);

		$data['packages'][] = array(
			'value' => '04',
			'text'  => $this->language->get('text_ups_pak')
		);

		$data['packages'][] = array(
			'value' => '21',
			'text'  => $this->language->get('text_ups_express_box')
		);

		$data['packages'][] = array(
			'value' => '24',
			'text'  => $this->language->get('text_ups_25kg_box')
		);

		$data['packages'][] = array(
			'value' => '25',
			'text'  => $this->language->get('text_ups_10kg_box')
		);

		if (isset($this->request->post['shipping_ups_classification'])) {
			$data['shipping_ups_classification'] = $this->request->post['shipping_ups_classification'];
		} else {
			$data['shipping_ups_classification'] = $this->config->get('shipping_ups_classification');
		}

		$data['classifications'][] = array(
			'value' => '01',
			'text'  => '01'
		);

		$data['classifications'][] = array(
			'value' => '03',
			'text'  => '03'
		);

		$data['classifications'][] = array(
			'value' => '04',
			'text'  => '04'
		);

		if (isset($this->request->post['shipping_ups_origin'])) {
			$data['shipping_ups_origin'] = $this->request->post['shipping_ups_origin'];
		} else {
			$data['shipping_ups_origin'] = $this->config->get('shipping_ups_origin');
		}

		$data['origins'] = array();

		$data['origins'][] = array(
			'value' => 'US',
			'text'  => $this->language->get('text_us')
		);

		$data['origins'][] = array(
			'value' => 'CA',
			'text'  => $this->language->get('text_ca')
		);

		$data['origins'][] = array(
			'value' => 'EU',
			'text'  => $this->language->get('text_eu')
		);

		$data['origins'][] = array(
			'value' => 'PR',
			'text'  => $this->language->get('text_pr')
		);

		$data['origins'][] = array(
			'value' => 'MX',
			'text'  => $this->language->get('text_mx')
		);

		$data['origins'][] = array(
			'value' => 'other',
			'text'  => $this->language->get('text_other')
		);

		if (isset($this->request->post['shipping_ups_city'])) {
			$data['shipping_ups_city'] = $this->request->post['shipping_ups_city'];
		} else {
			$data['shipping_ups_city'] = $this->config->get('shipping_ups_city');
		}

		if (isset($this->request->post['shipping_ups_state'])) {
			$data['shipping_ups_state'] = $this->request->post['shipping_ups_state'];
		} else {
			$data['shipping_ups_state'] = $this->config->get('shipping_ups_state');
		}

		if (isset($this->request->post['shipping_ups_country'])) {
			$data['shipping_ups_country'] = $this->request->post['shipping_ups_country'];
		} else {
			$data['shipping_ups_country'] = $this->config->get('shipping_ups_country');
		}

		if (isset($this->request->post['shipping_ups_postcode'])) {
			$data['shipping_ups_postcode'] = $this->request->post['shipping_ups_postcode'];
		} else {
			$data['shipping_ups_postcode'] = $this->config->get('shipping_ups_postcode');
		}

		if (isset($this->request->post['shipping_ups_test'])) {
			$data['shipping_ups_test'] = $this->request->post['shipping_ups_test'];
		} else {
			$data['shipping_ups_test'] = $this->config->get('shipping_ups_test');
		}

		if (isset($this->request->post['shipping_ups_quote_type'])) {
			$data['shipping_ups_quote_type'] = $this->request->post['shipping_ups_quote_type'];
		} else {
			$data['shipping_ups_quote_type'] = $this->config->get('shipping_ups_quote_type');
		}

		$data['quote_types'] = array();

		$data['quote_types'][] = array(
			'value' => 'residential',
			'text'  => $this->language->get('text_residential')
		);

		$data['quote_types'][] = array(
			'value' => 'commercial',
			'text'  => $this->language->get('text_commercial')
		);

		// US
		if (isset($this->request->post['shipping_ups_us_01'])) {
			$data['shipping_ups_us_01'] = $this->request->post['shipping_ups_us_01'];
		} else {
			$data['shipping_ups_us_01'] = $this->config->get('shipping_ups_us_01');
		}

		if (isset($this->request->post['shipping_ups_us_02'])) {
			$data['shipping_ups_us_02'] = $this->request->post['shipping_ups_us_02'];
		} else {
			$data['shipping_ups_us_02'] = $this->config->get('shipping_ups_us_02');
		}

		if (isset($this->request->post['shipping_ups_us_03'])) {
			$data['shipping_ups_us_03'] = $this->request->post['shipping_ups_us_03'];
		} else {
			$data['shipping_ups_us_03'] = $this->config->get('shipping_ups_us_03');
		}

		if (isset($this->request->post['shipping_ups_us_07'])) {
			$data['shipping_ups_us_07'] = $this->request->post['shipping_ups_us_07'];
		} else {
			$data['shipping_ups_us_07'] = $this->config->get('shipping_ups_us_07');
		}

		if (isset($this->request->post['shipping_ups_us_08'])) {
			$data['shipping_ups_us_08'] = $this->request->post['shipping_ups_us_08'];
		} else {
			$data['shipping_ups_us_08'] = $this->config->get('shipping_ups_us_08');
		}

		if (isset($this->request->post['shipping_ups_us_11'])) {
			$data['shipping_ups_us_11'] = $this->request->post['shipping_ups_us_11'];
		} else {
			$data['shipping_ups_us_11'] = $this->config->get('shipping_ups_us_11');
		}

		if (isset($this->request->post['shipping_ups_us_12'])) {
			$data['shipping_ups_us_12'] = $this->request->post['shipping_ups_us_12'];
		} else {
			$data['shipping_ups_us_12'] = $this->config->get('shipping_ups_us_12');
		}

		if (isset($this->request->post['shipping_ups_us_13'])) {
			$data['shipping_ups_us_13'] = $this->request->post['shipping_ups_us_13'];
		} else {
			$data['shipping_ups_us_13'] = $this->config->get('shipping_ups_us_13');
		}

		if (isset($this->request->post['shipping_ups_us_14'])) {
			$data['shipping_ups_us_14'] = $this->request->post['shipping_ups_us_14'];
		} else {
			$data['shipping_ups_us_14'] = $this->config->get('shipping_ups_us_14');
		}

		if (isset($this->request->post['shipping_ups_us_54'])) {
			$data['shipping_ups_us_54'] = $this->request->post['shipping_ups_us_54'];
		} else {
			$data['shipping_ups_us_54'] = $this->config->get('shipping_ups_us_54');
		}

		if (isset($this->request->post['shipping_ups_us_59'])) {
			$data['shipping_ups_us_59'] = $this->request->post['shipping_ups_us_59'];
		} else {
			$data['shipping_ups_us_59'] = $this->config->get('shipping_ups_us_59');
		}

		if (isset($this->request->post['shipping_ups_us_65'])) {
			$data['shipping_ups_us_65'] = $this->request->post['shipping_ups_us_65'];
		} else {
			$data['shipping_ups_us_65'] = $this->config->get('shipping_ups_us_65');
		}

		// Puerto Rico
		if (isset($this->request->post['shipping_ups_pr_01'])) {
			$data['shipping_ups_pr_01'] = $this->request->post['shipping_ups_pr_01'];
		} else {
			$data['shipping_ups_pr_01'] = $this->config->get('shipping_ups_pr_01');
		}

		if (isset($this->request->post['shipping_ups_pr_02'])) {
			$data['shipping_ups_pr_02'] = $this->request->post['shipping_ups_pr_02'];
		} else {
			$data['shipping_ups_pr_02'] = $this->config->get('shipping_ups_pr_02');
		}

		if (isset($this->request->post['shipping_ups_pr_03'])) {
			$data['shipping_ups_pr_03'] = $this->request->post['shipping_ups_pr_03'];
		} else {
			$data['shipping_ups_pr_03'] = $this->config->get('shipping_ups_pr_03');
		}

		if (isset($this->request->post['shipping_ups_pr_07'])) {
			$data['shipping_ups_pr_07'] = $this->request->post['shipping_ups_pr_07'];
		} else {
			$data['shipping_ups_pr_07'] = $this->config->get('shipping_ups_pr_07');
		}

		if (isset($this->request->post['shipping_ups_pr_08'])) {
			$data['shipping_ups_pr_08'] = $this->request->post['shipping_ups_pr_08'];
		} else {
			$data['shipping_ups_pr_08'] = $this->config->get('shipping_ups_pr_08');
		}

		if (isset($this->request->post['shipping_ups_pr_14'])) {
			$data['shipping_ups_pr_14'] = $this->request->post['shipping_ups_pr_14'];
		} else {
			$data['shipping_ups_pr_14'] = $this->config->get('shipping_ups_pr_14');
		}

		if (isset($this->request->post['shipping_ups_pr_54'])) {
			$data['shipping_ups_pr_54'] = $this->request->post['shipping_ups_pr_54'];
		} else {
			$data['shipping_ups_pr_54'] = $this->config->get('shipping_ups_pr_54');
		}

		if (isset($this->request->post['shipping_ups_pr_65'])) {
			$data['shipping_ups_pr_65'] = $this->request->post['shipping_ups_pr_65'];
		} else {
			$data['shipping_ups_pr_65'] = $this->config->get('shipping_ups_pr_65');
		}

		// Canada
		if (isset($this->request->post['shipping_ups_ca_01'])) {
			$data['shipping_ups_ca_01'] = $this->request->post['shipping_ups_ca_01'];
		} else {
			$data['shipping_ups_ca_01'] = $this->config->get('shipping_ups_ca_01');
		}

		if (isset($this->request->post['shipping_ups_ca_02'])) {
			$data['shipping_ups_ca_02'] = $this->request->post['shipping_ups_ca_02'];
		} else {
			$data['shipping_ups_ca_02'] = $this->config->get('shipping_ups_ca_02');
		}

		if (isset($this->request->post['shipping_ups_ca_07'])) {
			$data['shipping_ups_ca_07'] = $this->request->post['shipping_ups_ca_07'];
		} else {
			$data['shipping_ups_ca_07'] = $this->config->get('shipping_ups_ca_07');
		}

		if (isset($this->request->post['shipping_ups_ca_08'])) {
			$data['shipping_ups_ca_08'] = $this->request->post['shipping_ups_ca_08'];
		} else {
			$data['shipping_ups_ca_08'] = $this->config->get('shipping_ups_ca_08');
		}

		if (isset($this->request->post['shipping_ups_ca_11'])) {
			$data['shipping_ups_ca_11'] = $this->request->post['shipping_ups_ca_11'];
		} else {
			$data['shipping_ups_ca_11'] = $this->config->get('shipping_ups_ca_11');
		}

		if (isset($this->request->post['shipping_ups_ca_12'])) {
			$data['shipping_ups_ca_12'] = $this->request->post['shipping_ups_ca_12'];
		} else {
			$data['shipping_ups_ca_12'] = $this->config->get('shipping_ups_ca_12');
		}

		if (isset($this->request->post['shipping_ups_ca_13'])) {
			$data['shipping_ups_ca_13'] = $this->request->post['shipping_ups_ca_13'];
		} else {
			$data['shipping_ups_ca_13'] = $this->config->get('shipping_ups_ca_13');
		}

		if (isset($this->request->post['shipping_ups_ca_14'])) {
			$data['shipping_ups_ca_14'] = $this->request->post['shipping_ups_ca_14'];
		} else {
			$data['shipping_ups_ca_14'] = $this->config->get('shipping_ups_ca_14');
		}

		if (isset($this->request->post['shipping_ups_ca_54'])) {
			$data['shipping_ups_ca_54'] = $this->request->post['shipping_ups_ca_54'];
		} else {
			$data['shipping_ups_ca_54'] = $this->config->get('shipping_ups_ca_54');
		}

		if (isset($this->request->post['shipping_ups_ca_65'])) {
			$data['shipping_ups_ca_65'] = $this->request->post['shipping_ups_ca_65'];
		} else {
			$data['shipping_ups_ca_65'] = $this->config->get('shipping_ups_ca_65');
		}

		// Mexico
		if (isset($this->request->post['shipping_ups_mx_07'])) {
			$data['shipping_ups_mx_07'] = $this->request->post['shipping_ups_mx_07'];
		} else {
			$data['shipping_ups_mx_07'] = $this->config->get('shipping_ups_mx_07');
		}

		if (isset($this->request->post['shipping_ups_mx_08'])) {
			$data['shipping_ups_mx_08'] = $this->request->post['shipping_ups_mx_08'];
		} else {
			$data['shipping_ups_mx_08'] = $this->config->get('shipping_ups_mx_08');
		}

		if (isset($this->request->post['shipping_ups_mx_54'])) {
			$data['shipping_ups_mx_54'] = $this->request->post['shipping_ups_mx_54'];
		} else {
			$data['shipping_ups_mx_54'] = $this->config->get('shipping_ups_mx_54');
		}

		if (isset($this->request->post['shipping_ups_mx_65'])) {
			$data['shipping_ups_mx_65'] = $this->request->post['shipping_ups_mx_65'];
		} else {
			$data['shipping_ups_mx_65'] = $this->config->get('shipping_ups_mx_65');
		}

		// EU
		if (isset($this->request->post['shipping_ups_eu_07'])) {
			$data['shipping_ups_eu_07'] = $this->request->post['shipping_ups_eu_07'];
		} else {
			$data['shipping_ups_eu_07'] = $this->config->get('shipping_ups_eu_07');
		}

		if (isset($this->request->post['shipping_ups_eu_08'])) {
			$data['shipping_ups_eu_08'] = $this->request->post['shipping_ups_eu_08'];
		} else {
			$data['shipping_ups_eu_08'] = $this->config->get('shipping_ups_eu_08');
		}

		if (isset($this->request->post['shipping_ups_eu_11'])) {
			$data['shipping_ups_eu_11'] = $this->request->post['shipping_ups_eu_11'];
		} else {
			$data['shipping_ups_eu_11'] = $this->config->get('shipping_ups_eu_11');
		}

		if (isset($this->request->post['shipping_ups_eu_54'])) {
			$data['shipping_ups_eu_54'] = $this->request->post['shipping_ups_eu_54'];
		} else {
			$data['shipping_ups_eu_54'] = $this->config->get('shipping_ups_eu_54');
		}

		if (isset($this->request->post['shipping_ups_eu_65'])) {
			$data['shipping_ups_eu_65'] = $this->request->post['shipping_ups_eu_65'];
		} else {
			$data['shipping_ups_eu_65'] = $this->config->get('shipping_ups_eu_65');
		}

		if (isset($this->request->post['shipping_ups_eu_82'])) {
			$data['shipping_ups_eu_82'] = $this->request->post['shipping_ups_eu_82'];
		} else {
			$data['shipping_ups_eu_82'] = $this->config->get('shipping_ups_eu_82');
		}

		if (isset($this->request->post['shipping_ups_eu_83'])) {
			$data['shipping_ups_eu_83'] = $this->request->post['shipping_ups_eu_83'];
		} else {
			$data['shipping_ups_eu_83'] = $this->config->get('shipping_ups_eu_83');
		}

		if (isset($this->request->post['shipping_ups_eu_84'])) {
			$data['shipping_ups_eu_84'] = $this->request->post['shipping_ups_eu_84'];
		} else {
			$data['shipping_ups_eu_84'] = $this->config->get('shipping_ups_eu_84');
		}

		if (isset($this->request->post['shipping_ups_eu_85'])) {
			$data['shipping_ups_eu_85'] = $this->request->post['shipping_ups_eu_85'];
		} else {
			$data['shipping_ups_eu_85'] = $this->config->get('shipping_ups_eu_85');
		}

		if (isset($this->request->post['shipping_ups_eu_86'])) {
			$data['shipping_ups_eu_86'] = $this->request->post['shipping_ups_eu_86'];
		} else {
			$data['shipping_ups_eu_86'] = $this->config->get('shipping_ups_eu_86');
		}

		// Other
		if (isset($this->request->post['shipping_ups_other_07'])) {
			$data['shipping_ups_other_07'] = $this->request->post['shipping_ups_other_07'];
		} else {
			$data['shipping_ups_other_07'] = $this->config->get('shipping_ups_other_07');
		}

		if (isset($this->request->post['shipping_ups_other_08'])) {
			$data['shipping_ups_other_08'] = $this->request->post['shipping_ups_other_08'];
		} else {
			$data['shipping_ups_other_08'] = $this->config->get('shipping_ups_other_08');
		}

		if (isset($this->request->post['shipping_ups_other_11'])) {
			$data['shipping_ups_other_11'] = $this->request->post['shipping_ups_other_11'];
		} else {
			$data['shipping_ups_other_11'] = $this->config->get('shipping_ups_other_11');
		}

		if (isset($this->request->post['shipping_ups_other_54'])) {
			$data['shipping_ups_other_54'] = $this->request->post['shipping_ups_other_54'];
		} else {
			$data['shipping_ups_other_54'] = $this->config->get('shipping_ups_other_54');
		}

		if (isset($this->request->post['shipping_ups_other_65'])) {
			$data['shipping_ups_other_65'] = $this->request->post['shipping_ups_other_65'];
		} else {
			$data['shipping_ups_other_65'] = $this->config->get('shipping_ups_other_65');
		}

		if (isset($this->request->post['shipping_ups_display_weight'])) {
			$data['shipping_ups_display_weight'] = $this->request->post['shipping_ups_display_weight'];
		} else {
			$data['shipping_ups_display_weight'] = $this->config->get('shipping_ups_display_weight');
		}

		if (isset($this->request->post['shipping_ups_insurance'])) {
			$data['shipping_ups_insurance'] = $this->request->post['shipping_ups_insurance'];
		} else {
			$data['shipping_ups_insurance'] = $this->config->get('shipping_ups_insurance');
		}

		if (isset($this->request->post['shipping_ups_weight_class_id'])) {
			$data['shipping_ups_weight_class_id'] = $this->request->post['shipping_ups_weight_class_id'];
		} else {
			$data['shipping_ups_weight_class_id'] = $this->config->get('shipping_ups_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['shipping_ups_length_code'])) {
			$data['shipping_ups_length_code'] = $this->request->post['shipping_ups_length_code'];
		} else {
			$data['shipping_ups_length_code'] = $this->config->get('shipping_ups_length_code');
		}

		if (isset($this->request->post['shipping_ups_length_class_id'])) {
			$data['shipping_ups_length_class_id'] = $this->request->post['shipping_ups_length_class_id'];
		} else {
			$data['shipping_ups_length_class_id'] = $this->config->get('shipping_ups_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['shipping_ups_length'])) {
			$data['shipping_ups_length'] = $this->request->post['shipping_ups_length'];
		} else {
			$data['shipping_ups_length'] = $this->config->get('shipping_ups_length');
		}

		if (isset($this->request->post['shipping_ups_width'])) {
			$data['shipping_ups_width'] = $this->request->post['shipping_ups_width'];
		} else {
			$data['shipping_ups_width'] = $this->config->get('shipping_ups_width');
		}

		if (isset($this->request->post['shipping_ups_height'])) {
			$data['shipping_ups_height'] = $this->request->post['shipping_ups_height'];
		} else {
			$data['shipping_ups_height'] = $this->config->get('shipping_ups_height');
		}

		if (isset($this->request->post['shipping_ups_tax_class_id'])) {
			$data['shipping_ups_tax_class_id'] = $this->request->post['shipping_ups_tax_class_id'];
		} else {
			$data['shipping_ups_tax_class_id'] = $this->config->get('shipping_ups_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_ups_geo_zone_id'])) {
			$data['shipping_ups_geo_zone_id'] = $this->request->post['shipping_ups_geo_zone_id'];
		} else {
			$data['shipping_ups_geo_zone_id'] = $this->config->get('shipping_ups_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_ups_status'])) {
			$data['shipping_ups_status'] = $this->request->post['shipping_ups_status'];
		} else {
			$data['shipping_ups_status'] = $this->config->get('shipping_ups_status');
		}

		if (isset($this->request->post['shipping_ups_sort_order'])) {
			$data['shipping_ups_sort_order'] = $this->request->post['shipping_ups_sort_order'];
		} else {
			$data['shipping_ups_sort_order'] = $this->config->get('shipping_ups_sort_order');
		}

		if (isset($this->request->post['shipping_ups_debug'])) {
			$data['shipping_ups_debug'] = $this->request->post['shipping_ups_debug'];
		} else {
			$data['shipping_ups_debug'] = $this->config->get('shipping_ups_debug');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/ups', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/ups')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shipping_ups_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['shipping_ups_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['shipping_ups_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['shipping_ups_city']) {
			$this->error['city'] = $this->language->get('error_city');
		}

		if (!$this->request->post['shipping_ups_state']) {
			$this->error['state'] = $this->language->get('error_state');
		}

		if (!$this->request->post['shipping_ups_country']) {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (empty($this->request->post['shipping_ups_length'])) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		if (empty($this->request->post['shipping_ups_width'])) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		if (empty($this->request->post['shipping_ups_height'])) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		return !$this->error;
	}
}