<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionShippingXshipping extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/shipping/xshipping');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('xshipping', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->session->data['success'] = $this->language->get('text_success');	
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

        $data['tab_rate'] = $this->language->get('tab_rate');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_free'] = $this->language->get('entry_free');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_edit'] = $this->language->get('text_edit');
		
		$data['entry_cost'] = $this->language->get('entry_cost');
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/xshipping', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/xshipping', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);
		
		for($i=1;$i<=12;$i++)
		 {
				if (isset($this->request->post['xshipping_cost'.$i])) {
					$data['xshipping_cost'.$i] = $this->request->post['xshipping_cost'.$i];
				} else {
					$data['xshipping_cost'.$i] = $this->config->get('xshipping_cost'.$i);
				}
				
				if (isset($this->request->post['xshipping_name'.$i])) {
					$data['xshipping_name'.$i] = $this->request->post['xshipping_name'.$i];
				} else {
					$data['xshipping_name'.$i] = $this->config->get('xshipping_name'.$i);
				}
				
				if (isset($this->request->post['xshipping_free'.$i])) {
					$data['xshipping_free'.$i] = $this->request->post['xshipping_free'.$i];
				} else {
					$data['xshipping_free'.$i] = $this->config->get('xshipping_free'.$i);
				}
		
				if (isset($this->request->post['xshipping_tax_class_id'.$i])) {
					$data['xshipping_tax_class_id'.$i] = $this->request->post['xshipping_tax_class_id'.$i];
				} else {
					$data['xshipping_tax_class_id'.$i] = $this->config->get('xshipping_tax_class_id'.$i);
				}
		
				if (isset($this->request->post['xshipping_geo_zone_id'.$i])) {
					$data['xshipping_geo_zone_id'.$i] = $this->request->post['xshipping_geo_zone_id'.$i];
				} else {
					$data['xshipping_geo_zone_id'.$i] = $this->config->get('xshipping_geo_zone_id'.$i);
				}
				
				if (isset($this->request->post['xshipping_status'.$i])) {
					$data['xshipping_status'.$i] = $this->request->post['xshipping_status'.$i];
				} else {
					$data['xshipping_status'.$i] = $this->config->get('xshipping_status'.$i);
				}
				
				if (isset($this->request->post['xshipping_sort_order'.$i])) {
					$data['xshipping_sort_order'.$i] = $this->request->post['xshipping_sort_order'.$i];
				} else {
					$data['xshipping_sort_order'.$i] = $this->config->get('xshipping_sort_order'.$i);
				}
		 }	
		 
		 if (isset($this->request->post['xshipping_status'])) {
					$data['xshipping_status'] = $this->request->post['xshipping_status'];
				} else {
					$data['xshipping_status'] = $this->config->get('xshipping_status');
				}
		if (isset($this->request->post['xshipping_sort_order'])) {
					$data['xshipping_sort_order'] = $this->request->post['xshipping_sort_order'];
				} else {
					$data['xshipping_sort_order'] = $this->config->get('xshipping_sort_order');
				}						

		$this->load->model('localisation/tax_class');
		
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
									
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		 $this->response->setOutput($this->load->view('extension/shipping/xshipping', $data));
		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/xshipping')) {
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