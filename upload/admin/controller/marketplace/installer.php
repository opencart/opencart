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
		$data['button_uninstall'] = $this->language->get('button_uninstall');

		$data['tab_install'] = $this->language->get('tab_install');
		$data['tab_installed'] = $this->language->get('tab_installed');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	public function installed() {
		$this->load->language('marketplace/installer');

		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_filename'] = $this->language->get('column_filename');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		
		$this->load->model('setting/extension');
			
		$data['uploads'] = array();
		
		$results = $this->model_setting_extension->getUploads();
		
		foreach ($results as $result) {
			$data['uploads'][] = array(
				'extension_upload_id' => $result['extension_upload_id'],
				'filename'            => $result['filename'],
				'date_added'          => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
				
		$this->response->setOutput($this->load->view('marketplace/installer_installed', $data));
	}	
		
	public function upload() {
		$this->load->language('marketplace/installer');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Check if there is a install zip already there
		$file = ini_get('upload_tmp_dir') . '/install.tmp';

		if (is_file($file) && (filectime($file) < (time() - 5))) {
			unlink($file);
		}
		
		if (is_file($file)) {
			$json['error'] = $this->language->get('error_install');
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
			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			if (is_file($file)) {
				$this->load->model('setting/extension');
				
				$extension_upload_id = $this->model_setting_extension->addUpload($this->request->files['file']['name']);
				
				$json['text'] = $this->language->get('text_install');

				$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/install', 'user_token=' . $this->session->data['user_token'] . '&code=upload-' . $extension_upload_id, true));		
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}