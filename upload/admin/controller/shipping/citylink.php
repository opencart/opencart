<?php
class ControllerShippingCitylink extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/citylink');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('citylink', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
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
       		'href'      => HTTPS_SERVER . 'index.php?route=shipping/citylink&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=shipping/citylink&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['citylink_rate'])) {
			$this->data['citylink_rate'] = $this->request->post['citylink_rate'];
		} elseif ($this->config->get('citylink_rate')) {
			$this->data['citylink_rate'] = $this->config->get('citylink_rate');
		} else {
			$this->data['citylink_rate'] = '10:11.6,15:14.1,20:16.60,25:19.1,30:21.6,35:24.1,40:26.6,45:29.1,50:31.6,55:34.1,60:36.6,65:39.1,70:41.6,75:44.1,80:46.6,100:56.6,125:69.1,150:81.6,200:106.6';	
		}

		if (isset($this->request->post['citylink_tax_class_id'])) {
			$this->data['citylink_tax_class_id'] = $this->request->post['citylink_tax_class_id'];
		} else {
			$this->data['citylink_tax_class_id'] = $this->config->get('citylink_tax_class_id');
		}

		if (isset($this->request->post['citylink_geo_zone_id'])) {
			$this->data['citylink_geo_zone_id'] = $this->request->post['citylink_geo_zone_id'];
		} else {
			$this->data['citylink_geo_zone_id'] = $this->config->get('citylink_geo_zone_id');
		}
		
		if (isset($this->request->post['citylink_status'])) {
			$this->data['citylink_status'] = $this->request->post['citylink_status'];
		} else {
			$this->data['citylink_status'] = $this->config->get('citylink_status');
		}
		
		if (isset($this->request->post['citylink_sort_order'])) {
			$this->data['citylink_sort_order'] = $this->request->post['citylink_sort_order'];
		} else {
			$this->data['citylink_sort_order'] = $this->config->get('citylink_sort_order');
		}				

		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
								
		$this->template = 'shipping/citylink.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/citylink')) {
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