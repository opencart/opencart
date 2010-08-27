<?php
class ControllerExtensionModule extends Controller {
	public function index() {
		$this->load->language('extension/module');
		 
		$this->document->title = $this->language->get('heading_title'); 

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_position'] = $this->language->get('column_position');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
		
			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('module');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/module/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('module', $value);
				
				unset($extensions[$key]);
			}
		}
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/module/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->load->language('module/' . $extension);
	
				$action = array();
				
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => HTTPS_SERVER . 'index.php?route=extension/module/install&token=' . $this->session->data['token'] . '&extension=' . $extension
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => HTTPS_SERVER . 'index.php?route=module/' . $extension . '&token=' . $this->session->data['token']
					);
								
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => HTTPS_SERVER . 'index.php?route=extension/module/uninstall&token=' . $this->session->data['token'] . '&extension=' . $extension
					);
				}
				
				$postion = $this->config->get($extension . '_position');						
				
				if ($postion) {
					$postion = $this->language->get('text_' . $postion);
				} else {
					$postion = "";
				}
								
				$this->data['extensions'][] = array(
					'name'        => $this->language->get('heading_title'),
					'position'    => $postion,
					'status'      => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order'  => $this->config->get($extension . '_sort_order'),
					'action'      => $action
				);
			}
		}
		
		$this->template = 'extension/module.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		} else {
			$this->load->model('setting/extension');
			
			$this->model_setting_extension->install('module', $this->request->get['extension']);

			$this->load->model('user/user_group');
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);
			
			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');
			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'install')) {
				$class->install();
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		} else {		
			$this->load->model('setting/extension');
			$this->load->model('setting/setting');
					
			$this->model_setting_extension->uninstall('module', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');
			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);	
		}
	}
}
?>