<?php
class ControllerModuleSlideshow extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/slideshow');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('slideshow', $this->request->post);		
					
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
		
		$this->data['entry_banner'] = $this->language->get('entry_banner');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension'); 
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
		
		if (isset($this->request->post['slideshow_module'])) {
			$modules = explode(',', $this->request->post['slideshow_module']);
		} else {
			$modules = array();
		}	
		
		foreach ($modules as $module) {
			if (isset($this->error['dimension_' . $module])) {
				$this->data['error_dimension_' . $module] = $this->error['dimension_' . $module];
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
			'href'      => $this->url->link('module/slideshow', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/slideshow', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['slideshow_module'])) {
			$modules = explode(',', $this->request->post['slideshow_module']);
		} elseif ($this->config->get('slideshow_module') != '') {
			$modules = explode(',', $this->config->get('slideshow_module'));
		} else {
			$modules = array();
		}		
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('design/banner');
		
		$this->data['banners'] = $this->model_design_banner->getBanners();
						
		foreach ($modules as $module) {
			if (isset($this->request->post['slideshow_' . $module . '_banner_id'])) {
				$this->data['slideshow_' . $module . '_banner_id'] = $this->request->post['slideshow_' . $module . '_banner_id'];
			} else {
				$this->data['slideshow_' . $module . '_banner_id'] = $this->config->get('slideshow_' . $module . '_banner_id');
			}	

			if (isset($this->request->post['slideshow_' . $module . '_width'])) {
				$this->data['slideshow_' . $module . '_width'] = $this->request->post['slideshow_' . $module . '_width'];
			} else {
				$this->data['slideshow_' . $module . '_width'] = $this->config->get('slideshow_' . $module . '_width');
			}	
			
			if (isset($this->request->post['slideshow_' . $module . '_height'])) {
				$this->data['slideshow_' . $module . '_height'] = $this->request->post['slideshow_' . $module . '_height'];
			} else {
				$this->data['slideshow_' . $module . '_height'] = $this->config->get('slideshow_' . $module . '_height');
			}
						
			if (isset($this->request->post['slideshow_' . $module . '_layout_id'])) {
				$this->data['slideshow_' . $module . '_layout_id'] = $this->request->post['slideshow_' . $module . '_layout_id'];
			} else {
				$this->data['slideshow_' . $module . '_layout_id'] = $this->config->get('slideshow_' . $module . '_layout_id');
			}	
			
			if (isset($this->request->post['slideshow_' . $module . '_position'])) {
				$this->data['slideshow_' . $module . '_position'] = $this->request->post['slideshow_' . $module . '_position'];
			} else {
				$this->data['slideshow_' . $module . '_position'] = $this->config->get('slideshow_' . $module . '_position');
			}	
			
			if (isset($this->request->post['slideshow_' . $module . '_status'])) {
				$this->data['slideshow_' . $module . '_status'] = $this->request->post['slideshow_' . $module . '_status'];
			} else {
				$this->data['slideshow_' . $module . '_status'] = $this->config->get('slideshow_' . $module . '_status');
			}	
						
			if (isset($this->request->post['slideshow_' . $module . '_sort_order'])) {
				$this->data['slideshow_' . $module . '_sort_order'] = $this->request->post['slideshow_' . $module . '_sort_order'];
			} else {
				$this->data['slideshow_' . $module . '_sort_order'] = $this->config->get('slideshow_' . $module . '_sort_order');
			}				
		}
		
		$this->data['modules'] = $modules;
		
		if (isset($this->request->post['slideshow_module'])) {
			$this->data['slideshow_module'] = $this->request->post['slideshow_module'];
		} else {
			$this->data['slideshow_module'] = $this->config->get('slideshow_module');
		}

		$this->template = 'module/slideshow.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/slideshow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->request->post['slideshow_module'] !== '') {
			$modules = explode(',', $this->request->post['slideshow_module']);
		} else {
			$modules = array();
		}	
		
		foreach ($modules as $module) {
			if (!$this->request->post['slideshow_' . $module . '_width'] || !$this->request->post['slideshow_' . $module . '_height']) {
				$this->error['dimension_' . $module] = $this->language->get('error_dimension');
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