<?php
class ControllerExtensionModule extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module');

		$this->getList();
	}
	
	public function add() {
		$this->load->language('extension/module');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module->addModule($this->request->post);
			//$this->load->controller('module/' . $this->request->get['extension'] . '/install');
			// Add permissions
			//$this->load->model('user/user_group');
			
			//$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/' . $this->request->post['code']);
			//$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->post['code']);
			//$this->load->model('user/user_group');
		
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();		
	}
	
	public function delete() {
		$this->load->language('extension/module');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $module_id) {
				$this->model_extension_module->deleteModule($module_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();		
	}
	
	public function getList() {
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));
		$data['text_add'] = $this->language->get('text_add');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_name'] = $this->language->get('entry_name');
		
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_edit'] = $this->language->get('button_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}
				
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
			
		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} else {
			$data['code'] = '';
		}	
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} else {
			$data['name'] = '';
		}

		$data['add'] = $this->url->link('extension/module/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] = $this->url->link('extension/module/delete', 'token=' . $this->session->data['token'], 'SSL');

		// List all extensions
		$data['modules'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/module/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->load->language('module/' . $extension);
				
				$data['modules'][$extension] = array(
					'name' => $this->language->get('heading_title'),
					'code' => $extension
				);
			}
		}
		
		// List all modules
		$data['extensions'] = array();
		
		$modules = $this->model_extension_module->getModules();
		
		foreach ($modules as $module) {
			if (isset($data['modules'][$module['code']])) {
				// Add group if not set.	
				if (!isset($data['extensions'][$module['code']])) {
					$data['extensions'][$module['code']]['name'] = $data['modules'][$module['code']]['name'];
				}
				
				$data['extensions'][$module['code']]['module'][] = array(
					'module_id' => $module['module_id'],
					'name'      => $module['name'],
					'edit'      => $this->url->link('module/' . $module['code'], 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], 'SSL')
				);
			} else {
				// If file for extension is missing we delete the DB entry.
				$this->model_extension_module->deleteModule($module['module_id']);
			}
		}
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['code']) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
			
		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}	
}