<?php
class ControllerDesignTranslation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/translation');

		$this->getList();
	}

	public function edit() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/translation');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_translation->editTranslation($this->request->get['code'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
		
			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->response->redirect($this->url->link('design/translation', 'token=' . $this->session->data['token'] . $url, true));
		}

		return $this->getForm();
	}

	protected function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';

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
			'href' => $this->url->link('design/translation', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['files'] = array();

		$files = array();

		// Make path into an array
		$path = array(DIR_CATALOG . 'language/' . $this->config->get('config_language') . '/*');

		// While the path array is still populated keep looping through
		while (count($path) != 0) {
			$next = array_shift($path);

			foreach (glob($next) as $file) {
				// If directory add to path array
				if (is_dir($file)) {
					$path[] = $file . '/*';
				}

				// Add the file to the files to be deleted array
				if (is_file($file) && substr($file, -4) == '.php') {
					$files[] = substr($file, 0, -4);
				}
			}
		}
		
		// Get total number of files and directories
		$translation_total = count($files);
			
		$files = array_splice($files, ($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'));	
		
		foreach ($files as $file) {
			$code = substr($file, strlen(DIR_CATALOG . '/language/' . $this->config->get('config_language')));
			
			$data['files'][] = array(
				'route' => $code,
				'total' => $this->model_design_translation->getTotalTranslationsByRoute($code),
				'edit'  => $this->url->link('design/translation/edit', 'token=' . $this->session->data['token'] . '&code=' . $code . $url, true)
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_route'] = $this->language->get('column_route');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_edit'] = $this->language->get('button_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$pagination = new Pagination();
		$pagination->total = $translation_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/translation', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($translation_total - $this->config->get('config_limit_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $translation_total, ceil($translation_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_list', $data));
	}

	protected function getForm() {
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}		
		
		$directory = DIR_CATALOG . 'language/' . $this->config->get('config_language') . '/';
		
		if (is_file($directory . $code . '.php') && substr(str_replace('\\', '/', realpath($directory . $code . '.php')), 0, strlen(DIR_CATALOG . 'language/')) == DIR_CATALOG . 'language/') {
			$data['heading_title'] = $this->language->get('heading_title');
	
			$data['text_form'] = $this->language->get('text_edit');
			$data['text_default'] = $this->language->get('text_default');
			$data['text_loading'] = $this->language->get('text_loading');
			
			$data['entry_store'] = $this->language->get('entry_store');
			$data['entry_language'] = $this->language->get('entry_language');
			$data['entry_key'] = $this->language->get('entry_key');
			$data['entry_value'] = $this->language->get('entry_value');
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_translation_add'] = $this->language->get('button_translation_add');
			$data['button_remove'] = $this->language->get('button_remove');
			$data['button_translation'] = $this->language->get('button_translation');
	
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}
			
			$url = '';
			
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
				'href' => $this->url->link('design/translation', 'token=' . $this->session->data['token'] . $url, true)
			);
	
			$data['action'] = $this->url->link('design/translation/edit', 'token=' . $this->session->data['token'] . '&code=' . $this->request->get['code'] . $url, true);
	
			$data['cancel'] = $this->url->link('design/translation', 'token=' . $this->session->data['token'] . $url, true);
	
			$data['token'] = $this->session->data['token'];
			
			$data['code'] = $this->request->get['code'];
			
			if (isset($this->request->post['translations'])) {
				$data['translations'] = $this->request->post['translations'];
			} elseif (isset($this->request->get['code'])) {
				$data['translations'] = $this->model_design_translation->getTranslationsByRoute($this->request->get['code']);
			} else {
				$data['translations'] = array();
			}
			
			$this->load->model('setting/store');
	
			$data['stores'] = $this->model_setting_store->getStores();
			
			$this->load->model('localisation/language');
	
			$data['languages'] = $this->model_localisation_language->getLanguages();
			
			$_ = array();
					
			include($directory . $code . '.php');
		
			$data['keys'] = array_keys($_);
		
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
	
			$this->response->setOutput($this->load->view('design/translation_form', $data));
		} else {
			return new Action('error/not_found');
		}			
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function translation() {
		$json = array();
			
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
					
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 0;
		}	
		
		if (isset($this->request->get['key'])) {
			$key = $this->request->get['key'];
		} else {
			$key = '';
		}	
					
		$this->load->model('localisation/language');
		
		$language_info = $this->model_localisation_language->getLanguage($language_id);
		
		if ($language_info) {
			$directory = DIR_CATALOG . 'language/';
			
			if (is_file($directory . $language_info['code'] . '/' . $code . '.php') && substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $code . '.php')), 0, strlen($directory)) == $directory) {
				$_ = array();
						
				include($directory . $language_info['code'] . '/' . $code . '.php');	
				
				if (isset($_[$key])) {
					$json = $_[$key];
				}
			}
		}
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}