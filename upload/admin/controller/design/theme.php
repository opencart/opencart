<?php
class ControllerDesignTheme extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/theme', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['themes'] = array();

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_reset'] = $this->language->get('button_reset');
		
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme', $data));
	}

	public function directory() {
		$this->load->language('design/theme');
		
		$json = array();
				
		if (!$json) {
			$json['file'] = array();
			
			// If no store ID is set we need to show the list of avaliable stores.
			if (!isset($this->request->get['store_id'])) {
				$json['directory'][] = array(
					'name' => $this->language->get('text_default'),
					'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=0', true)
				);		
				
				$this->load->model('setting/store');
							
				$results = $this->model_setting_store->getStores();
				
				foreach ($results as $result) {
					$json['directory'][] = array(
						'name' => $result['name'],
						'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], true)
					);
				}
			} elseif (!isset($this->request->get['theme'])) {
				// If no theme is set we need to show the list of avaliable themes.
				$this->load->model('extension/theme');
							
				$results = $this->model_extension_theme->getThemes();
				
				foreach ($results as $result) {
					$json['directory'][] = array(
						'name' => $result['name'],
						'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], true)
					);
				}				
			} else {
				if (isset($this->request->get['directory'])) {
					$directory = $this->request->get['directory'];
				} else {
					$directory = '';
				}
				
				if (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/' . $directory)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view') {
					$files = glob(DIR_CATALOG . 'view/' . $directory . '/*');
					
					if ($files) {
						foreach($files as $file) {
							if (is_dir($file)) {
								$json['directory'][] = array(
									'name' => basename($file),
									'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&directory=' . trim($directory . '/' . basename($file), '/'), true)
								);
							}
							
							if (is_file($file)) {
								$json['file'][] = array(
									'name' => basename($file),
									'href' => $this->url->link('design/theme/code', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&file=' . basename($file), true)
								);
							}			
						}
					}
				}
				
				if (!$directory) {
					$json['back'] = array(
						'name' => $this->language->get('button_back'),
						'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'], true)
					);
				} else {
					$url = '';
					
					$pos = strrpos($directory, '/');

					if ($pos !== false) {
						$url .= '&directory=' . urlencode(substr($directory, 0, $pos));
					}
					
					$json['back'] = array(
						'name' => $this->language->get('button_back'),
						'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . $url, true)
					);					
				}
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function code() {
		$this->load->language('design/theme');

		$json = array();

		$this->load->model('design/theme');

		$theme_info = $this->model_design_theme->getThemeByRoute($this->request->get['route']);

		if ($theme_info) {
			$json['code'] = $theme_info['code'];
		} else {
			if (isset($this->request->get['directory'])) {
				$directory = $this->request->get['directory'];
			} else {
				$directory = '';
			}
			
			if (isset($this->request->get['theme'])) {
				$theme = $this->request->get['theme'];
			} else {
				$theme = '';
			}
									
			if (isset($this->request->get['file'])) {
				$file = $this->request->get['file'];
			} else {
				$file = '';
			}			
			
			if (is_file(DIR_CATALOG . 'view/' . $directory . '/' . $file) && substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/' . $directory . '/' . $file)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view') {
			//	basename($file)
			
				$url = '';
				
				$pos = strrpos($directory, '/');

				if ($pos !== false) {
					$url .= '&directory=' . urlencode(substr($directory, 0, $pos));
				}	
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function save() {
		$this->load->language('design/theme');
		
		$json = array();
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

      //  if () {
			$this->load->model('design/theme');
		
			$this->model_design_theme->editCode($this->request->get['route'], $this->request->post['code']);

		
	//	}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function reset() {
		$this->load->language('design/theme');

		$json = array();
		
		$this->load->model('design/theme');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}
