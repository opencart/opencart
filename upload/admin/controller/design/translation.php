<?php
class ControllerDesignTranslation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_loading'] = $this->language->get('text_loading');		
		$data['text_store'] = $this->language->get('text_store');
		$data['text_language'] = $this->language->get('text_language');
		$data['text_translation'] = $this->language->get('text_translation');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_history'] = $this->language->get('text_history');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_reset'] = $this->language->get('button_reset');
		
		$data['token'] = $this->session->data['token'];
		
		$data['stores'] = array();
		
		$this->load->model('setting/store');
					
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		$data['languages'] = array();
		
		$this->load->model('localisation/language');
					
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name']
			);
		}
		
		$data['language_id'] = $this->config->get('config_language_id');
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation', $data));
	}

	public function history() {
		$this->load->language('design/translation');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_store'] = $this->language->get('column_store');
		$data['column_route'] = $this->language->get('column_route');
		$data['column_language'] = $this->language->get('column_language');
		$data['column_key'] = $this->language->get('column_key');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$data['histories'] = array();
		
		$this->load->model('design/translation');
		$this->load->model('setting/store');
		
		$results = $this->model_design_translation->getTranslations(($page - 1) * 10, 10);
					
		foreach ($results as $result) {
			$store_info = $this->model_setting_store->getStore($result['store_id']);
			
			if ($store_info) {
				$store = $store_info['name'];
			} else {
				$store = '';
			}
			
			$data['histories'][] = array(
				'store_id' => $result['store_id'],
				'store'    => ($result['store_id'] ? $store : $this->language->get('text_default')),
				'route'    => $result['route'],
				'language' => $result['language'],
				'key'      => $result['key'],
				'edit'     => $this->url->link('design/translation/edit', 'token=' . $this->session->data['token'], true),
				'delete'   => $this->url->link('design/translation/delete', 'token=' . $this->session->data['token'] . '&translation_id=' . $result['translation_id'], true)
			);			
		}
		
		$history_total = $this->model_design_translation->getTotalTranslations();

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('design/translation/history', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('design/translation_history', $data));
	}
	
	public function path() {
		$this->load->language('design/translation');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];			
		} else {
			$language_id = 0;
		}
						
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		$this->load->model('design/translation');

		$this->load->model('localisation/language');
					
		$language_info = $this->model_localisation_language->getLanguage($language_id);
		
		if ($language_info && (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'language/' . $language_info['code'] . '/' . $path)), 0, strlen(DIR_CATALOG . 'language')) == DIR_CATALOG . 'language')) {
			$path_data = array();
			
			// We grab the files from the default theme directory first as the custom themes drops back to the default theme if selected theme files can not be found.
			$files = glob(rtrim(DIR_CATALOG . 'language/{en-gb,' . $language_info['code'] . '}/' . $path, '/') . '/*', GLOB_BRACE);
			
			if ($files) {
				foreach($files as $file) {
					if (!in_array(basename($file), $path_data))  {
						if (is_dir($file)) {
							$json['directory'][] = array(
								'name' => basename($file),
								'path' => trim($path . '/' . basename($file), '/')
							);
						}
						
						if (is_file($file) && (substr($file, -4) == '.php')) {
							$translation_total = $this->model_design_language->getTotalTranslations($store_id, $language_id, trim($path . '/' . basename($file, '.php'), '/'));
							
							$json['file'][] = array(
								'name' => basename($file, '.php') . ' (' . $translation_total . ')',
								'path' => trim($path . '/' . basename($file, '.php'), '/')
							);
						}
						
						$path_data[] = basename($file);
					}
				}
			}
		}

		if (!empty($this->request->get['path'])) {
			$json['back'] = array(
				'name' => $this->language->get('button_back'),
				'path' => urlencode(substr($path, 0, strrpos($path, '/')))
			);
		}		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function translation() {
		$this->load->language('design/translation');
		
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_default'] = $this->language->get('entry_default');
		$data['entry_value'] = $this->language->get('entry_value');
		
		$data['button_save'] = $this->language->get('button_save');
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];			
		} else {
			$language_id = 0;
		}
		
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}	
		
		$data['translations'] = array();
		
		$this->load->model('localisation/language');
					
		$language_info = $this->model_localisation_language->getLanguage($language_id);
		
		$directory = DIR_CATALOG . 'language/';
		
		if ($language_info && is_file($directory . $language_info['code'] . '/' . $path . '.php') && (substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $path . '.php')), 0, strlen(DIR_CATALOG . 'language')) == DIR_CATALOG . 'language')) {
			$translation_data = array();
			
			$this->load->model('design/translation');
					
			$results = $this->model_design_language->getTranslations($store_id, $language_id, $path);
		
			foreach ($results as $result) {
				$translation_data[$result['key']] = $result['value'];
			}			
			
			print_r($translation_data);
			
			$_ = array();
						
			include($directory . $language_info['code'] . '/' . $path . '.php');	
			
			foreach ($_ as $key => $value) {
				$data['translations'][] = array(
					'key'     => $key,
					'default' => $value,
					'value'   => isset($translation_data[$key]) ? $translation_data[$key] : ''
				);
			}
		}

		$this->response->setOutput($this->load->view('design/translation_translation', $data));
	}		
	
	public function save() {
		$this->load->language('design/translation');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}
			
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];			
		} else {
			$language_id = 0;
		}		
		
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}		
			
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('design/translation');
			
			$this->model_design_language->editTranslation($store_id, $language_id, $path, $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function delete() {
		$this->load->language('design/translation');
		
		$json = array();
		
		if (isset($this->request->get['translation_id'])) {
			$translation_id = $this->request->get['translation_id'];			
		} else {
			$translation_id = 0;
		}	
	
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		} 
		
		if (!$json) { 		
			$this->load->model('design/translation');
		
			$this->model_design_translation->deleteTranslation($translation_id);

			$json['success'] = $this->language->get('text_success');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}