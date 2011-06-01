<?php
class ControllerModuleWelcome extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load->language('module/welcome');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('welcome', $this->request->post);		
			
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
		
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['tab_module'] = $this->language->get('tab_module');
		
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
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
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/welcome', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/welcome', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['welcome_module'])) {
			$modules = explode(',', $this->request->post['welcome_module']);
		} elseif ($this->config->get('welcome_module') != '') {
			$modules = explode(',', $this->config->get('welcome_module'));
		} else {
			$modules = array();
		}	
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
				
		foreach ($modules as $module) {
			foreach ($languages as $language) {
				if (isset($this->request->post['welcome_' . $module . '_description_' . $language['language_id']])) {
					$this->data['welcome_' . $module . '_description_' . $language['language_id']] = $this->request->post['welcome_' . $module . '_description_' . $language['language_id']];
				} else {
					$this->data['welcome_' . $module . '_description_' . $language['language_id']] = $this->config->get('welcome_' . $module . '_description_' . $language['language_id']);
				}
			}
			
			if (isset($this->request->post['welcome_' . $module . '_layout_id'])) {
				$this->data['welcome_' . $module . '_layout_id'] = $this->request->post['welcome_' . $module . '_layout_id'];
			} else {
				$this->data['welcome_' . $module . '_layout_id'] = $this->config->get('welcome_' . $module . '_layout_id');
			}	
						
			if (isset($this->request->post['welcome_' . $module . '_position'])) {
				$this->data['welcome_' . $module . '_position'] = $this->request->post['welcome_' . $module . '_position'];
			} else {
				$this->data['welcome_' . $module . '_position'] = $this->config->get('welcome_' . $module . '_position');
			}	
						
			if (isset($this->request->post['welcome_' . $module . '_status'])) {
				$this->data['welcome_' . $module . '_status'] = $this->request->post['welcome_' . $module . '_status'];
			} else {
				$this->data['welcome_' . $module . '_status'] = $this->config->get('welcome_' . $module . '_status');
			}
			
			if (isset($this->request->post['welcome_' . $module . '_sort_order'])) {
				$this->data['welcome_' . $module . '_sort_order'] = $this->request->post['welcome_' . $module . '_sort_order'];
			} else {
				$this->data['welcome_' . $module . '_sort_order'] = $this->config->get('welcome_' . $module . '_sort_order');
			}							
		}
		
		$this->data['modules'] = $modules;
		$this->data['languages'] = $languages;
		
		if (isset($this->request->post['welcome_module'])) {
			$this->data['welcome_module'] = $this->request->post['welcome_module'];
		} else {
			$this->data['welcome_module'] = $this->config->get('welcome_module');
		}

		$this->template = 'module/welcome.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/welcome')) {
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