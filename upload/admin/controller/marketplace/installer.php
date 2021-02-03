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

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	public function history() {
		$this->load->language('marketplace/installer');
		
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
					
		$data['histories'] = array();
		
		$this->load->model('setting/extension');
		
		$results = $this->model_setting_extension->getExtensionInstalls(($page - 1) * 10, 10);
		
		foreach ($results as $result) {
			$data['histories'][] = array(
				'extension_install_id' => $result['extension_install_id'],
				'filename'             => $result['filename'],
				'date_added'           => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		
		$history_total = $this->model_setting_extension->getTotalExtensionInstalls();

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('marketplace/installer/history', 'user_token=' . $this->session->data['user_token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));
				
		$this->response->setOutput($this->load->view('marketplace/installer_history', $data));
	}	
		
	public function upload() {
		$this->load->language('marketplace/installer');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Check if there is a install zip already there
		$files = glob(DIR_UPLOAD . '*.tmp');

		foreach ($files as $file) {
			if (is_file($file) && (filectime($file) < (time() - 5))) {
				unlink($file);
			}
			
			if (is_file($file)) {
				$json['error'] = $this->language->get('error_install');
				
				break;
			}
		}

		// Check for any install directories
		$directories = glob(DIR_UPLOAD . 'tmp-*');
		
		foreach ($directories as $directory) {
			if (is_dir($directory) && (filectime($directory) < (time() - 5))) {
				// Get a list of files ready to upload
				$files = array();
	
				$path = array($directory);
	
				while (count($path) != 0) {
					$next = array_shift($path);
	
					// We have to use scandir function because glob will not pick up dot files.
					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;
	
						if (is_dir($file)) {
							$path[] = $file;
						}
	
						$files[] = $file;
					}
				}
	
				rsort($files);
	
				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
	
				rmdir($directory);
			}
			
			if (is_dir($directory)) {
				$json['error'] = $this->language->get('error_install');
				
				break;
			}		
		}
		
		if (isset($this->request->files['file']['name'])) {
			if (substr($this->request->files['file']['name'], -10) != '.ocmod.zip') {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$this->session->data['install'] = token(10);
			
			$file = DIR_UPLOAD . $this->session->data['install'] . '.tmp';
			
			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			if (is_file($file)) {
				$this->load->model('setting/extension');
				
				$extension_install_id = $this->model_setting_extension->addExtensionInstall($this->request->files['file']['name']);
				
				$json['text'] = $this->language->get('text_install');

				$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id, true));		
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}