<?php
class ControllerShippingWeight extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('shipping/weight');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('weight', $this->request->post);	
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_enabled_no_geo'] = $this->language->get('text_enabled_no_geo');
		
		$this->data['entry_base_cost'] = $this->language->get('entry_base_cost');
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_allzones'] = $this->language->get('tab_allzones');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'		=> HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'		=> $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'		=> HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'],
			'text'		=> $this->language->get('text_shipping'),
			'separator'	=> ' :: '
		);
		
		$this->document->breadcrumbs[] = array(
			'href'		=> HTTPS_SERVER . 'index.php?route=shipping/weight&token=' . $this->session->data['token'],
			'text'		=> $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=shipping/weight&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'];
		
		$this->load->model('localisation/geo_zone');
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_basecost'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_basecost'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_basecost'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_basecost'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_basecost');
			}
			
			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_rate');
			}
			
			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_status');
			}
		}
		
		$this->data['geo_zones'] = $geo_zones;
		
		if (isset($this->request->post['weight_tax_class_id'])) {
			$this->data['weight_tax_class_id'] = $this->request->post['weight_tax_class_id'];
		} else {
			$this->data['weight_tax_class_id'] = $this->config->get('weight_tax_class_id');
		}
		
		if (isset($this->request->post['weight_status'])) {
			$this->data['weight_status'] = $this->request->post['weight_status'];
		} else {
			$this->data['weight_status'] = $this->config->get('weight_status');
		}
		
		if (isset($this->request->post['weight_sort_order'])) {
			$this->data['weight_sort_order'] = $this->request->post['weight_sort_order'];
		} else {
			$this->data['weight_sort_order'] = $this->config->get('weight_sort_order');
		}
		
		if (isset($this->request->post['weight_allzones_basecost'])) {
			$this->data['weight_allzones_basecost'] = $this->request->post['weight_allzones_basecost'];
		} else {
			$this->data['weight_allzones_basecost'] = $this->config->get('weight_allzones_basecost');
		}
		
		if (isset($this->request->post['weight_allzones_rate'])) {
			$this->data['weight_allzones_rate'] = $this->request->post['weight_allzones_rate'];
		} else {
			$this->data['weight_allzones_rate'] = $this->config->get('weight_allzones_rate');
		}
		
		if (isset($this->request->post['weight_allzones_status'])) {
			$this->data['weight_allzones_status'] = $this->request->post['weight_allzones_status'];
		} else {
			$this->data['weight_allzones_status'] = $this->config->get('weight_allzones_status');
		}
		
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->template = 'shipping/weight.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/weight')) {
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