<?php
class ControllerShippingRoyalMail extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/royal_mail');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('royal_mail', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_1st_class_standard'] = $this->language->get('text_1st_class_standard');
		$this->data['text_1st_class_recorded'] = $this->language->get('text_1st_class_recorded');
		$this->data['text_2nd_class_standard'] = $this->language->get('text_2nd_class_standard');
		$this->data['text_2nd_class_recorded'] = $this->language->get('text_2nd_class_recorded');
		$this->data['text_standard_parcels'] = $this->language->get('text_standard_parcels');
		$this->data['text_airmail'] = $this->language->get('text_airmail');
		$this->data['text_international_signed'] = $this->language->get('text_international_signed');
		$this->data['text_airsure'] = $this->language->get('text_airsure');
		$this->data['text_surface'] = $this->language->get('text_surface');
		
		$this->data['entry_service'] = $this->language->get('entry_service');
		$this->data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$this->data['entry_display_insurance'] = $this->language->get('entry_display_insurance');
		$this->data['entry_display_time'] = $this->language->get('entry_display_time');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning']))  {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=shipping/royal_mail&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=shipping/royal_mail&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'];

		if (isset($this->request->post['royal_mail_1st_class_standard'])) {
			$this->data['royal_mail_1st_class_standard'] = $this->request->post['royal_mail_1st_class_standard'];
		} else {
			$this->data['royal_mail_1st_class_standard'] = $this->config->get('royal_mail_1st_class_standard');
		}
		
		if (isset($this->request->post['royal_mail_1st_class_standard'])) {
			$this->data['royal_mail_1st_class_standard'] = $this->request->post['royal_mail_1st_class_standard'];
		} else {
			$this->data['royal_mail_1st_class_standard'] = $this->config->get('royal_mail_1st_class_standard');
		}
		
		if (isset($this->request->post['royal_mail_1st_class_recorded'])) {
			$this->data['royal_mail_1st_class_recorded'] = $this->request->post['royal_mail_1st_class_recorded'];
		} else {
			$this->data['royal_mail_1st_class_recorded'] = $this->config->get('royal_mail_1st_class_recorded');
		}
		
		if (isset($this->request->post['royal_mail_2nd_class_standard'])) {
			$this->data['royal_mail_2nd_class_standard'] = $this->request->post['royal_mail_2nd_class_standard'];
		} else {
			$this->data['royal_mail_2nd_class_standard'] = $this->config->get('royal_mail_2nd_class_standard');
		}
		
		if (isset($this->request->post['royal_mail_2nd_class_recorded'])) {
			$this->data['royal_mail_2nd_class_recorded'] = $this->request->post['royal_mail_2nd_class_recorded'];
		} else {
			$this->data['royal_mail_2nd_class_recorded'] = $this->config->get('royal_mail_2nd_class_recorded');
		}
		
		if (isset($this->request->post['royal_mail_standard_parcels'])) {
			$this->data['royal_mail_standard_parcels'] = $this->request->post['royal_mail_standard_parcels'];
		} else {
			$this->data['royal_mail_standard_parcels'] = $this->config->get('royal_mail_standard_parcels');
		}
		
		if (isset($this->request->post['royal_mail_airmail'])) {
			$this->data['royal_mail_airmail'] = $this->request->post['royal_mail_airmail'];
		} else {
			$this->data['royal_mail_airmail'] = $this->config->get('royal_mail_airmail');
		}
		
		if (isset($this->request->post['royal_mail_international_signed'])) {
			$this->data['royal_mail_international_signed'] = $this->request->post['royal_mail_international_signed'];
		} else {
			$this->data['royal_mail_international_signed'] = $this->config->get('royal_mail_international_signed');
		}
		
		if (isset($this->request->post['royal_mail_airsure'])) {
			$this->data['royal_mail_airsure'] = $this->request->post['royal_mail_airsure'];
		} else {
			$this->data['royal_mail_airsure'] = $this->config->get('royal_mail_airsure');
		}
		
		if (isset($this->request->post['royal_mail_surface'])) {
			$this->data['royal_mail_surface'] = $this->request->post['royal_mail_surface'];
		} else {
			$this->data['royal_mail_surface'] = $this->config->get('royal_mail_surface');
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
		
		if (isset($this->request->post['royal_mail_display_time'])) {
			$this->data['royal_mail_display_time'] = $this->request->post['royal_mail_display_time'];
		} else {
			$this->data['royal_mail_display_time'] = $this->config->get('royal_mail_display_time');
		}

		if (isset($this->request->post['royal_mail_weight_class'])) {
			$this->data['royal_mail_weight_class'] = $this->request->post['royal_mail_weight_class'];
		} else {
			$this->data['royal_mail_weight_class'] = $this->config->get('royal_mail_weight_class');
		}
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
		if (isset($this->request->post['royal_mail_tax_class_id'])) {
			$this->data['royal_mail_tax_class_id'] = $this->request->post['royal_mail_tax_class_id'];
		} else {
			$this->data['royal_mail_tax_class_id'] = $this->config->get('royal_mail_tax_class_id');
		}

		if (isset($this->request->post['royal_mail_geo_zone_id'])) {
			$this->data['royal_mail_geo_zone_id'] = $this->request->post['royal_mail_geo_zone_id'];
		} else {
			$this->data['royal_mail_geo_zone_id'] = $this->config->get('royal_mail_geo_zone_id');
		}
		
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

		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->template = 'shipping/royal_mail.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/royal_mail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>