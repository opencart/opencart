<?php
class ControllerModuleLatest extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/latest');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('latest', $this->request->post);		
			
			$this->cache->delete('product');
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->request->post['latest_module'])) {
			$modules = explode(',', $this->request->post['latest_module']);
		} else {
			$modules = array();
		}	
		
		foreach ($modules as $module) {
			if (isset($this->error['image_' . $module])) {
				$this->data['error_image_' . $module] = $this->error['image_' . $module];
			}
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/latest', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/latest', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['latest_module'])) {
			$modules = explode(',', $this->request->post['latest_module']);
		} elseif ($this->config->get('latest_module') != '') {
			$modules = explode(',', $this->config->get('latest_module'));
		} else {
			$modules = array();
		}		
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
				
		foreach ($modules as $module) {
			if (isset($this->request->post['latest_' . $module . '_limit'])) {
				$this->data['latest_' . $module . '_limit'] = $this->request->post['latest_' . $module . '_limit'];
			} else {
				$this->data['latest_' . $module . '_limit'] = $this->config->get('latest_' . $module . '_limit');
			}			

			if (isset($this->request->post['latest_' . $module . '_image_width'])) {
				$this->data['latest_' . $module . '_image_width'] = $this->request->post['latest_' . $module . '_image_width'];
			} else {
				$this->data['latest_' . $module . '_image_width'] = $this->config->get('latest_' . $module . '_image_width');
			}
			
			if (isset($this->request->post['latest_' . $module . '_image_height'])) {
				$this->data['latest_' . $module . '_image_height'] = $this->request->post['latest_' . $module . '_image_height'];
			} else {
				$this->data['latest_' . $module . '_image_height'] = $this->config->get('latest_' . $module . '_image_height');
			}
						
			if (isset($this->request->post['latest_' . $module . '_layout_id'])) {
				$this->data['latest_' . $module . '_layout_id'] = $this->request->post['latest_' . $module . '_layout_id'];
			} else {
				$this->data['latest_' . $module . '_layout_id'] = $this->config->get('latest_' . $module . '_layout_id');
			}	
			
			if (isset($this->request->post['latest_' . $module . '_position'])) {
				$this->data['latest_' . $module . '_position'] = $this->request->post['latest_' . $module . '_position'];
			} else {
				$this->data['latest_' . $module . '_position'] = $this->config->get('latest_' . $module . '_position');
			}	
			
			if (isset($this->request->post['latest_' . $module . '_status'])) {
				$this->data['latest_' . $module . '_status'] = $this->request->post['latest_' . $module . '_status'];
			} else {
				$this->data['latest_' . $module . '_status'] = $this->config->get('latest_' . $module . '_status');
			}	
						
			if (isset($this->request->post['latest_' . $module . '_sort_order'])) {
				$this->data['latest_' . $module . '_sort_order'] = $this->request->post['latest_' . $module . '_sort_order'];
			} else {
				$this->data['latest_' . $module . '_sort_order'] = $this->config->get('latest_' . $module . '_sort_order');
			}				
		}
		
		$this->data['modules'] = $modules;
		
		if (isset($this->request->post['latest_module'])) {
			$this->data['latest_module'] = $this->request->post['latest_module'];
		} else {
			$this->data['latest_module'] = $this->config->get('latest_module');
		}

		$this->template = 'module/latest.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/latest')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->request->post['latest_module'] !== '') {
			$modules = explode(',', $this->request->post['latest_module']);
		} else {
			$modules = array();
		}	
		
		foreach ($modules as $module) {
			if (!$this->request->post['latest_' . $module . '_image_width'] || !$this->request->post['latest_' . $module . '_image_height']) {
				$this->error['image_' . $module] = $this->language->get('error_image');
			}	
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>