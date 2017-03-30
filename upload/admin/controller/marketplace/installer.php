<?php
class ControllerMarketplaceInstaller extends Controller {
	public function index() {
		$this->load->language('marketplace/installer');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/installer', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['heading_title'] = $this->language->get('heading_title');
        
		$data['text_upload'] = $this->language->get('text_upload');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_progress'] = $this->language->get('text_progress');
			
		$data['entry_upload'] = $this->language->get('entry_upload');
		$data['entry_progress'] = $this->language->get('entry_progress');
	
		$data['help_upload'] = $this->language->get('help_upload');
		
		$data['button_upload'] = $this->language->get('button_upload');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}
	
	public function upload() {
		$this->load->language('marketplace/installer');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->files['file']['name'])) {
			if (substr($this->request->files['file']['name'], -10) != '.ocmod.zip' && substr($this->request->files['file']['name'], -10) != '.ocmod.xml') {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = ini_get('upload_tmp_dir') . '/install.tmp';

			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			if (is_file($file)) {
				$json['text'] = $this->language->get('text_install');

				$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/install', 'user_token=' . $this->session->data['user_token'], true));		
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}