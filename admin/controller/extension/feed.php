<?php
class ControllerExtensionFeed extends Controller {
	public function index() {
		$this->load->language('extension/feed');
		 
		$this->document->title = $this->language->get('heading_title'); 

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extention/feed'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_development'] = $this->language->get('column_development');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('feed');
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/feed/*.php');
		
		foreach ($files as $file) {
			$extension = basename($file, '.php');
			
			$this->load->language('feed/' . $extension);

			$action = array();
			
			if (!in_array($extension, $extensions)) {
				$action[] = array(
					'text' => $this->language->get('text_install'),
					'href' => $this->url->https('extension/feed/install&extension=' . $extension)
				);
			} else {
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->https('feed/' . $extension)
				);
							
				$action[] = array(
					'text' => $this->language->get('text_uninstall'),
					'href' => $this->url->https('extension/feed/uninstall&extension=' . $extension)
				);
			}
									
			$this->data['extensions'][] = array(
				'name'        => $this->language->get('heading_title'),
				'development' => $this->language->get('text_development'),
				'status'      => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'action'      => $action
			);
		}
						
		$this->id       = 'content';
		$this->template = 'extension/feed.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
	}
	
	public function install() {
		$this->load->model('setting/extension');
		
		$this->model_setting_extension->install('feed', $this->request->get['extension']);
		
		$this->redirect($this->url->https('extension/feed'));
	}
	
	public function uninstall() {
		$this->load->model('setting/extension');
		$this->load->model('setting/setting');
		
		$this->model_setting_extension->uninstall('feed', $this->request->get['extension']);
		
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
		$this->redirect($this->url->https('extension/feed'));	
	}
}
?>