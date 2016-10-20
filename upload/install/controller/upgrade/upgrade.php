<?php
class ControllerUpgradeUpgrade extends Controller {
	public function index() {
		$this->language->load('upgrade/upgrade');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_upgrade'] = $this->language->get('text_upgrade');
		$data['text_server'] = $this->language->get('text_server');
		$data['text_steps'] = $this->language->get('text_steps');
		$data['text_error'] = $this->language->get('text_error');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_admin'] = $this->language->get('text_admin');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_store'] = $this->language->get('text_store');
		
		$data['entry_progress'] = $this->language->get('entry_progress');
		
		$data['button_continue'] = $this->language->get('button_continue');

		$data['store'] = HTTP_OPENCART;

		$data['total'] = count(glob(DIR_APPLICATION . 'model/upgrade/*.php'));

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('upgrade/upgrade', $data));
	}
	
	public function next() {
		$this->load->language('upgrade/upgrade');
				
		$json = array();

		if (isset($this->request->get['step'])) {
			$step = $this->request->get['step'];
		} else {
			$step = 1;
		}
		
		$files = glob(DIR_APPLICATION . 'model/upgrade/*.php');

		if (isset($files[$step - 1])) {
			// Get the upgrade file
			try {
				$this->load->model('upgrade/' . basename($files[$step - 1], '.php'));
				
				// All upgrade methods require a upgrade method
				$this->{'model_upgrade_' . str_replace('.', '', basename($files[$step - 1], '.php'))}->upgrade();
			
				$json['success'] = sprintf($this->language->get('text_progress'), basename($files[$step - 1], '.php'), $step, count($files));
			
				$json['next'] = str_replace('&amp;', '&', $this->url->link('upgrade/upgrade/next', 'step=' . ($step + 1)));
			} catch(Exception $exception) {
				$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
			}		
		} else {
			$json['success'] = $this->language->get('text_success');
		}
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
}