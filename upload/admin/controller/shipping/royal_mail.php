<?php
class ControllerShippingRoyalMail extends Controller {
	private $error = array(); 

	public function index() {   
		$this->language->load('shipping/royal_mail');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('royal_mail', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');		

		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_insurance'] = $this->language->get('entry_insurance');
		$this->data['entry_airmail_rate_1'] = $this->language->get('entry_airmail_rate_1');
		$this->data['entry_airmail_rate_2'] = $this->language->get('entry_airmail_rate_2');
		$this->data['entry_international_signed_rate_1'] = $this->language->get('entry_international_signed_rate_1');
		$this->data['entry_international_signed_insurance_1'] = $this->language->get('entry_international_signed_insurance_1');
		$this->data['entry_international_signed_rate_2'] = $this->language->get('entry_international_signed_rate_2');
		$this->data['entry_international_signed_insurance_2'] = $this->language->get('entry_international_signed_insurance_2');	
		$this->data['entry_airsure_rate_1'] = $this->language->get('entry_airsure_rate_1');
		$this->data['entry_airsure_insurance_1'] = $this->language->get('entry_airsure_insurance_1');
		$this->data['entry_airsure_rate_2'] = $this->language->get('entry_airsure_rate_2');
		$this->data['entry_airsure_insurance_2'] = $this->language->get('entry_airsure_insurance_2');	
		$this->data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$this->data['entry_display_insurance'] = $this->language->get('entry_display_insurance');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_1st_class_standard'] = $this->language->get('tab_1st_class_standard');
		$this->data['tab_1st_class_recorded'] = $this->language->get('tab_1st_class_recorded');
		$this->data['tab_2nd_class_standard'] = $this->language->get('tab_2nd_class_standard');
		$this->data['tab_2nd_class_recorded'] = $this->language->get('tab_2nd_class_recorded');
		$this->data['tab_special_delivery_500'] = $this->language->get('tab_special_delivery_500');
		$this->data['tab_special_delivery_1000'] = $this->language->get('tab_special_delivery_1000');
		$this->data['tab_special_delivery_2500'] = $this->language->get('tab_special_delivery_2500');		
		$this->data['tab_standard_parcels'] = $this->language->get('tab_standard_parcels');
		$this->data['tab_airmail'] = $this->language->get('tab_airmail');
		$this->data['tab_international_signed'] = $this->language->get('tab_international_signed');
		$this->data['tab_airsure'] = $this->language->get('tab_airsure');
		$this->data['tab_surface'] = $this->language->get('tab_surface');

		if (isset($this->error['warning']))  {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/royal_mail', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('shipping/royal_mail', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		// 1st Class Standard
		if (isset($this->request->post['royal_mail_1st_class_standard_rate'])) {
			$this->data['royal_mail_1st_class_standard_rate'] = $this->request->post['royal_mail_1st_class_standard_rate'];
		} elseif ($this->config->has('royal_mail_1st_class_standard_rate')) {
			$this->data['royal_mail_1st_class_standard_rate'] = $this->config->get('royal_mail_1st_class_standard_rate');
		} else {
			$this->data['royal_mail_1st_class_standard_rate'] = '.1:1.58,.25:1.96,.5:2.48,.75:3.05,1:3.71,1.25:4.90,1.5:5.66,1.75:6.42,2:7.18,4:8.95,6:12.00,8:15.05,10:18.10';
		}

		if (isset($this->request->post['royal_mail_1st_class_standard_insurance'])) {
			$this->data['royal_mail_1st_class_standard_insurance'] = $this->request->post['royal_mail_1st_class_standard_insurance'];
		} elseif ($this->config->has('royal_mail_1st_class_standard_insurance')) {
			$this->data['royal_mail_1st_class_standard_insurance'] = $this->config->get('royal_mail_1st_class_standard_insurance');
		} else {
			$this->data['royal_mail_1st_class_standard_insurance'] = '39:0,100:1,250:2.25,500:3.5';
		}

		if (isset($this->request->post['royal_mail_1st_class_standard_status'])) {
			$this->data['royal_mail_1st_class_standard_status'] = $this->request->post['royal_mail_1st_class_standard_status'];
		} else {
			$this->data['royal_mail_1st_class_standard_status'] = $this->config->get('royal_mail_1st_class_standard_status');
		}

		// 1st Class Recorded		
		if (isset($this->request->post['royal_mail_1st_class_recorded_rate'])) {
			$this->data['royal_mail_1st_class_recorded_rate'] = $this->request->post['royal_mail_1st_class_recorded_rate'];
		} elseif ($this->config->has('royal_mail_1st_class_recorded_rate')) {
			$this->data['royal_mail_1st_class_recorded_rate'] = $this->config->get('royal_mail_1st_class_recorded_rate');
		} else {
			$this->data['royal_mail_1st_class_recorded_rate'] = '.1:2.35,.25:2.73,.5:3.25,.75:3.82,1:4.86,1.25:5.67,1.5:6.43,1.75:7.19,2:7.95,4:9.72,6:12.77,8:15.82,10:18.87';
		}

		if (isset($this->request->post['royal_mail_1st_class_recorded_insurance'])) {
			$this->data['royal_mail_1st_class_recorded_insurance'] = $this->request->post['royal_mail_1st_class_recorded_insurance'];
		} elseif ($this->config->has('royal_mail_1st_class_recorded_insurance')) {
			$this->data['royal_mail_1st_class_recorded_insurance'] = $this->config->get('royal_mail_1st_class_recorded_insurance');
		} else {
			$this->data['royal_mail_1st_class_recorded_insurance'] = '39:46,100:46,250:46,500:46';
		}

		if (isset($this->request->post['royal_mail_1st_class_recorded_status'])) {
			$this->data['royal_mail_1st_class_recorded_status'] = $this->request->post['royal_mail_1st_class_recorded_status'];
		} else {
			$this->data['royal_mail_1st_class_recorded_status'] = $this->config->get('royal_mail_1st_class_recorded_status');
		}

		// 2nd Class Standard	
		if (isset($this->request->post['royal_mail_2nd_class_standard_rate'])) {
			$this->data['royal_mail_2nd_class_standard_rate'] = $this->request->post['royal_mail_2nd_class_standard_rate'];
		} elseif ($this->config->has('royal_mail_2nd_class_standard_rate')) {
			$this->data['royal_mail_2nd_class_standard_rate'] = $this->config->get('royal_mail_2nd_class_standard_rate');
		} else {
			$this->data['royal_mail_2nd_class_standard_rate'] = '.1:1.33,.25:1.72,.5:2.16,.75:2.61,1:3.15';
		}

		if (isset($this->request->post['royal_mail_2nd_class_standard_status'])) {
			$this->data['royal_mail_2nd_class_standard_status'] = $this->request->post['royal_mail_2nd_class_standard_status'];
		} else {
			$this->data['royal_mail_2nd_class_standard_status'] = $this->config->get('royal_mail_2nd_class_standard_status');
		}

		// 2nd Class Recorded
		if (isset($this->request->post['royal_mail_2nd_class_recorded_rate'])) {
			$this->data['royal_mail_2nd_class_recorded_rate'] = $this->request->post['royal_mail_2nd_class_recorded_rate'];
		} elseif ($this->config->has('royal_mail_2nd_class_recorded_rate')) {
			$this->data['royal_mail_2nd_class_recorded_rate'] = $this->config->get('royal_mail_2nd_class_recorded_rate');
		} else {
			$this->data['royal_mail_2nd_class_recorded_rate'] = '.1:2.10,.25:2.49,.5:2.93,.75:3.38,1:3.92';
		}

		if (isset($this->request->post['royal_mail_2nd_class_recorded_insurance'])) {
			$this->data['royal_mail_2nd_class_recorded_insurance'] = $this->request->post['royal_mail_2nd_class_recorded_insurance'];
		} elseif ($this->config->has('royal_mail_2nd_class_recorded_insurance')) {
			$this->data['royal_mail_2nd_class_recorded_insurance'] = $this->config->get('royal_mail_2nd_class_recorded_insurance');
		} else {
			$this->data['royal_mail_2nd_class_recorded_insurance'] = '39:46,100:46,250:46,500:46';
		}

		if (isset($this->request->post['royal_mail_2nd_class_recorded_status'])) {
			$this->data['royal_mail_2nd_class_recorded_status'] = $this->request->post['royal_mail_2nd_class_recorded_status'];
		} else {
			$this->data['royal_mail_2nd_class_recorded_status'] = $this->config->get('royal_mail_2nd_class_recorded_status');
		}

		// Special Delivery < 500
		if (isset($this->request->post['royal_mail_special_delivery_500_rate'])) {
			$this->data['royal_mail_special_delivery_500_rate'] = $this->request->post['royal_mail_special_delivery_500_rate'];
		} elseif ($this->config->has('royal_mail_special_delivery_500_rate')) {
			$this->data['royal_mail_special_delivery_500_rate'] = $this->config->get('royal_mail_special_delivery_500_rate');
		} else {
			$this->data['royal_mail_special_delivery_500_rate'] = '.1:5.45,.5:5.90,1:7.00,2:9.05,10:22.70';
		}

		if (isset($this->request->post['royal_mail_special_delivery_500_insurance'])) {
			$this->data['royal_mail_special_delivery_500_insurance'] = $this->request->post['royal_mail_special_delivery_500_insurance'];
		} elseif ($this->config->has('royal_mail_special_delivery_500_insurance')) {
			$this->data['royal_mail_special_delivery_500_insurance'] = $this->config->get('royal_mail_special_delivery_500_insurance');
		} else {
			$this->data['royal_mail_special_delivery_500_insurance'] = '39:500,100:500,250:500,500:500';
		}	

		if (isset($this->request->post['royal_mail_special_delivery_500_status'])) {
			$this->data['royal_mail_special_delivery_500_status'] = $this->request->post['royal_mail_special_delivery_500_status'];
		} else {
			$this->data['royal_mail_special_delivery_500_status'] = $this->config->get('royal_mail_special_delivery_500_status');
		}

		// Special Delivery < 1000
		if (isset($this->request->post['royal_mail_special_delivery_1000_rate'])) {
			$this->data['royal_mail_special_delivery_1000_rate'] = $this->request->post['royal_mail_special_delivery_1000_rate'];
		} elseif ($this->config->has('royal_mail_special_delivery_1000_rate')) {
			$this->data['royal_mail_special_delivery_1000_rate'] = $this->config->get('royal_mail_special_delivery_1000_rate');
		} else {
			$this->data['royal_mail_special_delivery_1000_rate'] = '.1:6.35,.5:6.80,1:7.90,2:9.95,10:23.60';
		}

		if (isset($this->request->post['royal_mail_special_delivery_1000_insurance'])) {
			$this->data['royal_mail_special_delivery_1000_insurance'] = $this->request->post['royal_mail_special_delivery_1000_insurance'];
		} elseif ($this->config->has('royal_mail_special_delivery_1000_insurance')) {
			$this->data['royal_mail_special_delivery_1000_insurance'] = $this->config->get('royal_mail_special_delivery_1000_insurance');
		} else {
			$this->data['royal_mail_special_delivery_1000_insurance'] = '39:1000,100:1000,250:1000,500:1000';
		}	

		if (isset($this->request->post['royal_mail_special_delivery_1000_status'])) {
			$this->data['royal_mail_special_delivery_1000_status'] = $this->request->post['royal_mail_special_delivery_1000_status'];
		} else {
			$this->data['royal_mail_special_delivery_1000_status'] = $this->config->get('royal_mail_special_delivery_1000_status');
		}

		// Special Delivery < 2500
		if (isset($this->request->post['royal_mail_special_delivery_2500_rate'])) {
			$this->data['royal_mail_special_delivery_2500_rate'] = $this->request->post['royal_mail_special_delivery_2500_rate'];
		} elseif ($this->config->has('royal_mail_special_delivery_2500_rate')) {
			$this->data['royal_mail_special_delivery_2500_rate'] = $this->config->get('royal_mail_special_delivery_2500_rate');
		} else {
			$this->data['royal_mail_special_delivery_2500_rate'] = '.1:8.20,.5:8.65,1:9.75,2:11.80,10:25.45';
		}

		if (isset($this->request->post['royal_mail_special_delivery_2500_insurance'])) {
			$this->data['royal_mail_special_delivery_2500_insurance'] = $this->request->post['royal_mail_special_delivery_2500_insurance'];
		} elseif ($this->config->has('royal_mail_special_delivery_2500_insurance')) {
			$this->data['royal_mail_special_delivery_2500_insurance'] = $this->config->get('royal_mail_special_delivery_2500_insurance');
		} else {
			$this->data['royal_mail_special_delivery_2500_insurance'] = '39:2500,100:2500,250:2500,500:2500';
		}

		if (isset($this->request->post['royal_mail_special_delivery_2500_status'])) {
			$this->data['royal_mail_special_delivery_2500_status'] = $this->request->post['royal_mail_special_delivery_2500_status'];
		} else {
			$this->data['royal_mail_special_delivery_2500_status'] = $this->config->get('royal_mail_special_delivery_2500_status');
		}

		// Standard Parcels
		if (isset($this->request->post['royal_mail_standard_parcels_rate'])) {
			$this->data['royal_mail_standard_parcels_rate'] = $this->request->post['royal_mail_standard_parcels_rate'];
		} elseif ($this->config->has('royal_mail_standard_parcels_rate')) {
			$this->data['royal_mail_standard_parcels_rate'] = $this->config->get('royal_mail_standard_parcels_rate');
		} else {
			$this->data['royal_mail_standard_parcels_rate'] = '2:4.41,4:7.66,6:10.43,8:12.67,10:13.61,20:15.86';
		}

		if (isset($this->request->post['royal_mail_standard_parcels_insurance'])) {
			$this->data['royal_mail_standard_parcels_insurance'] = $this->request->post['royal_mail_standard_parcels_insurance'];
		} elseif ($this->config->has('royal_mail_standard_parcels_insurance')) {
			$this->data['royal_mail_standard_parcels_insurance'] = $this->config->get('royal_mail_standard_parcels_insurance');		
		} else {
			$this->data['royal_mail_standard_parcels_insurance'] = '39:0,100:1,250:2.25,500:3.5';
		}

		if (isset($this->request->post['royal_mail_standard_parcels_status'])) {
			$this->data['royal_mail_standard_parcels_status'] = $this->request->post['royal_mail_standard_parcels_status'];
		} else {
			$this->data['royal_mail_standard_parcels_status'] = $this->config->get('royal_mail_standard_parcels_status');
		}

		// Airmail
		if (isset($this->request->post['royal_mail_airmail_rate_1'])) {
			$this->data['royal_mail_airmail_rate_1'] = $this->request->post['royal_mail_airmail_rate_1'];
		} elseif ($this->config->has('royal_mail_airmail_rate_1')) {
			$this->data['royal_mail_airmail_rate_1'] = $this->config->get('royal_mail_airmail_rate_1');
		} else {
			$this->data['royal_mail_airmail_rate_1'] = '0.01:1.49,0.02:1.49,0.04:1.49,0.06:1.49,0.08:1.49,0.1:1.49,0.12:1.61,0.14:1.79,0.16:1.93,0.18:2.11,0.2:2.19,0.22:2.29,0.24:2.39,0.26:2.49,0.28:2.56,0.30:2.61';
		}

		if (isset($this->request->post['royal_mail_airmail_rate_2'])) {
			$this->data['royal_mail_airmail_rate_2'] = $this->request->post['royal_mail_airmail_rate_2'];
		} elseif ($this->config->has('royal_mail_airmail_rate_2')) {
			$this->data['royal_mail_airmail_rate_2'] = $this->config->get('royal_mail_airmail_rate_2');
		} else {
			$this->data['royal_mail_airmail_rate_2'] = '0.02:2.07,0.02:2.07,0.04:2.07,0.06:2.07,0.08:2.07,0.1:2.07,0.12:2.32,0.14:2.60,0.16:2.90,0.18:3.20,0.2:3.50,0.22:3.80,0.24:3.96,0.26:4.06,0.28:4.16,0.30:4.26';
		}

		if (isset($this->request->post['royal_mail_airmail_status'])) {
			$this->data['royal_mail_airmail_status'] = $this->request->post['royal_mail_airmail_status'];
		} else {
			$this->data['royal_mail_airmail_status'] = $this->config->get('royal_mail_airmail_status');
		}

		// International Signed
		if (isset($this->request->post['royal_mail_international_signed_rate_1'])) {
			$this->data['royal_mail_international_signed_rate_1'] = $this->request->post['royal_mail_international_signed_rate_1'];
		} elseif ($this->config->has('royal_mail_international_signed_rate_1')) {
			$this->data['royal_mail_international_signed_rate_1'] = $this->config->get('royal_mail_international_signed_rate_1');
		} else {
			$this->data['royal_mail_international_signed_rate_1'] = '.1:6.44,.12:6.56,.14:6.74,.16:6.88,.18:7.06,.2:7.14,.22:7.24,.24:7.34,.26:7.44,.28:7.51,.3:7.56,.4:8.11,.5:8.66,.6:9.21,.7:9.76,.8:10.31,.9:10.86,1:11.41,1.2:12.51,1.4:13.61,1.6:14.71,1.8:15.81,2:16.91';
		}

		if (isset($this->request->post['royal_mail_international_signed_insurance_1'])) {
			$this->data['royal_mail_international_signed_insurance_1'] = $this->request->post['royal_mail_international_signed_insurance_1'];
		} elseif ($this->config->has('royal_mail_international_signed_insurance_1')) {
			$this->data['royal_mail_international_signed_insurance_1'] = $this->config->get('royal_mail_international_signed_insurance_1');
		} else {
			$this->data['royal_mail_international_signed_insurance_1'] = '39:0,250:2.20';
		}

		if (isset($this->request->post['royal_mail_international_signed_rate_2'])) {
			$this->data['royal_mail_international_signed_rate_2'] = $this->request->post['royal_mail_international_signed_rate_2'];
		} elseif ($this->config->has('royal_mail_international_signed_rate_2')) {
			$this->data['royal_mail_international_signed_rate_2'] = $this->config->get('royal_mail_international_signed_rate_2');
		} else {
			$this->data['royal_mail_international_signed_rate_2'] = '.1:7.37,.12:7.62,.14:7.90,.16:8.20,.18:8.50,.2:8.80,.22:9.10,.24:9.26,.26:9.36,.28:9.46,.3:9.56,.4:10.67,.5:11.78,.6:12.89,.7:14,.8:15.11,.9:16.22,1:17.33,1.2:19.55,1.4:21.77,1.6:23.99,1.8:26.21,2:28.43';
		}

		if (isset($this->request->post['royal_mail_international_signed_insurance_2'])) {
			$this->data['royal_mail_international_signed_insurance_2'] = $this->request->post['royal_mail_international_signed_insurance_2'];
		} elseif ($this->config->has('royal_mail_international_signed_insurance_2')) {
			$this->data['royal_mail_international_signed_insurance_2'] = $this->config->get('royal_mail_international_signed_insurance_2');
		} else {
			$this->data['royal_mail_international_signed_insurance_2'] = '39:0,250:2.20';
		}

		if (isset($this->request->post['royal_mail_international_signed_status'])) {
			$this->data['royal_mail_international_signed_status'] = $this->request->post['royal_mail_international_signed_status'];
		} else {
			$this->data['royal_mail_international_signed_status'] = $this->config->get('royal_mail_international_signed_status');
		}

		// Airsure
		if (isset($this->request->post['royal_mail_airsure_rate_1'])) {
			$this->data['royal_mail_airsure_rate_1'] = $this->request->post['royal_mail_airsure_rate_1'];
		} elseif ($this->config->has('royal_mail_airsure_rate_1')) {
			$this->data['royal_mail_airsure_rate_1'] = $this->config->get('royal_mail_airsure_rate_1');
		} else {
			$this->data['royal_mail_airsure_rate_1'] = '.1:6.79,.12:6.91,.14:7.09,.16:7.23,.18:7.41,.2:7.49,.22:7.59,.24:7.69,.26:7.79,.28:7.86,.3:7.91,.4:8.46,.5:9.01,.6:9.56,.7:10.11,.5:10.66,.7:11.21,.8:11.76,.9:12.31,1:12.86';
		}

		if (isset($this->request->post['royal_mail_airsure_insurance_1'])) {
			$this->data['royal_mail_airsure_insurance_1'] = $this->request->post['royal_mail_airsure_insurance_1'];
		} elseif ($this->config->has('royal_mail_airsure_insurance_1')) {
			$this->data['royal_mail_airsure_insurance_1'] = $this->config->get('royal_mail_airsure_insurance_1');
		} else {
			$this->data['royal_mail_airsure_insurance_1'] = '39:0,500:2.2';
		}

		if (isset($this->request->post['royal_mail_airsure_rate_2'])) {
			$this->data['royal_mail_airsure_rate_2'] = $this->request->post['royal_mail_airsure_rate_2'];
		} elseif ($this->config->has('royal_mail_airsure_rate_2')) {
			$this->data['royal_mail_airsure_rate_2'] = $this->config->get('royal_mail_airsure_rate_2');
		} else {
			$this->data['royal_mail_airsure_rate_2'] = '.1:7.37,.12:7.62,.14:7.90,.16:8.20,.18:8.5,.2:8.80,.22:9.10,.24:9.26,.26:9.36,.28:9.46,.3:9.56,.4:10.67,.5:11.78,.6:12.89,.7:14.00,.5:15.11,.7:16.22,.8:17.33,.9:18.44,1:19.55';
		}

		if (isset($this->request->post['royal_mail_airsure_insurance_2'])) {
			$this->data['royal_mail_airsure_insurance_2'] = $this->request->post['royal_mail_airsure_insurance_2'];
		} elseif ($this->config->has('royal_mail_airsure_insurance_2')) {
			$this->data['royal_mail_airsure_insurance_2'] = $this->config->get('royal_mail_airsure_insurance_2');
		} else {
			$this->data['royal_mail_airsure_insurance_2'] = '39:0,500:2.2';
		}

		if (isset($this->request->post['royal_mail_airsure_status'])) {
			$this->data['royal_mail_airsure_status'] = $this->request->post['royal_mail_airsure_status'];
		} else {
			$this->data['royal_mail_airsure_status'] = $this->config->get('royal_mail_airsure_status');
		}

		// Surface
		if (isset($this->request->post['royal_mail_surface_rate'])) {
			$this->data['royal_mail_surface_rate'] = $this->request->post['royal_mail_surface_rate'];
		} elseif ($this->config->has('royal_mail_surface_rate')) {
			$this->data['royal_mail_surface_rate'] = $this->config->get('royal_mail_surface_rate');
		} else {
			$this->data['royal_mail_surface_rate'] = '.1:0.91,.15:1.22,.2:1.53,.25:1.84,.3:2.14,.35:2.44,.4:2.76,.45:3.06,.5:3.36,.55:3.67,.6:3.98,.65:4.28,.7:4.59,.75:4.89,.8:5.2,.85:5.5,.9:5.81,1:6.42,1.1:7.03,1.2:7.65,1.3:8.25,1.4:8.87,1.5:9.48,1.6:10.09,1.7:10.61,1.8:11.13,1.9:11.65,2:12.17';
		}

		if (isset($this->request->post['royal_mail_surface_status'])) {
			$this->data['royal_mail_surface_status'] = $this->request->post['royal_mail_surface_status'];
		} else {
			$this->data['royal_mail_surface_status'] = $this->config->get('royal_mail_surface_status');
		}

		if (isset($this->request->post['royal_mail_display_weight'])) {
			$this->data['royal_mail_display_weight'] = $this->request->post['royal_mail_display_weight'];
		} else {
			$this->data['royal_mail_display_weight'] = $this->config->get('royal_mail_display_weight');
		}

		if (isset($this->request->post['royal_mail_display_insurance'])) {
			$this->data['royal_mail_display_insurance'] = $this->request->post['royal_mail_display_insurance'];
		} else {
			$this->data['royal_mail_display_insurance'] = $this->config->get('royal_mail_display_insurance');
		}

		if (isset($this->request->post['royal_mail_weight_class_id'])) {
			$this->data['royal_mail_weight_class_id'] = $this->request->post['royal_mail_weight_class_id'];
		} else {
			$this->data['royal_mail_weight_class_id'] = $this->config->get('royal_mail_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['royal_mail_tax_class_id'])) {
			$this->data['royal_mail_tax_class_id'] = $this->request->post['royal_mail_tax_class_id'];
		} else {
			$this->data['royal_mail_tax_class_id'] = $this->config->get('royal_mail_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['royal_mail_geo_zone_id'])) {
			$this->data['royal_mail_geo_zone_id'] = $this->request->post['royal_mail_geo_zone_id'];
		} else {
			$this->data['royal_mail_geo_zone_id'] = $this->config->get('royal_mail_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['royal_mail_status'])) {
			$this->data['royal_mail_status'] = $this->request->post['royal_mail_status'];
		} else {
			$this->data['royal_mail_status'] = $this->config->get('royal_mail_status');
		}

		if (isset($this->request->post['royal_mail_sort_order'])) {
			$this->data['royal_mail_sort_order'] = $this->request->post['royal_mail_sort_order'];
		} else {
			$this->data['royal_mail_sort_order'] = $this->config->get('royal_mail_sort_order');
		}				

		$this->template = 'shipping/royal_mail.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/royal_mail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>