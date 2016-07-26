<?php
class ControllerToolMaintenance extends Controller {
	public function index() {
		$directories = glob(ini_get('upload_tmp_dir') . '/lng-*');

		if ($directories) {
			$data['error_warning'] = $this->language->get('error_temporary');
		} else {
			$data['error_warning'] = '';
		}

		$directories = glob(ini_get('upload_tmp_dir') . '/ext-*', GLOB_ONLYDIR);

		if ($directories) {
			$data['error_warning'] = $this->language->get('error_temporary');
		} else {
			$data['error_warning'] = '';
		}
		
		$directories = glob(ini_get('upload_tmp_dir') . '/bac-*', GLOB_ONLYDIR);

		if ($directories) {
			$data['error_warning'] = $this->language->get('error_temporary');
		} else {
			$data['error_warning'] = '';
		}		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/translation', $data));
	}
		
	public function clear() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Remove and language files
			$files = glob(ini_get('upload_tmp_dir') . '/lng-*.zip');
			
			foreach ($files as $file) {
				unlink($file);
			}
			
			// Remove and language directories
			$directories = glob(ini_get('upload_tmp_dir') . '/lng-*', GLOB_ONLYDIR);

			foreach ($directories as $directory) {
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

				if (is_dir($directory)) {
					rmdir($directory);
				}
			}
			
			$json['success'] = $this->language->get('text_clear');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}