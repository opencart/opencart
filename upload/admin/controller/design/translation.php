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
			$this->model_design_translation->editTranslation($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('design/translation', 'token=' . $this->session->data['token'], true));
		}

		$this->getForm();
	}

	protected function getList() {
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'token=' . $this->session->data['token'], true)
		);

		$data['directories'] = array();

		$directories = glob(DIR_CATALOG . '/language/' . $this->config->get('config_language') . '/' . $path . '/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$code = substr($file, strlen(DIR_CATALOG . '/language/' . $this->config->get('config_language') . '/'));
			
			$data['directories'][] = array(
				'filename' => $code,
				'path'     => $this->url->link('design/translation', 'token=' . $this->session->data['token'] . '&path=' . urlencode($code), true)
			);
		}
		
		$data['files'] = array();
		
		$files = glob(DIR_CATALOG . '/language/' . $this->config->get('config_language') . '/' . $path . '*.php');
		
		foreach ($files as $file) {
			$code = substr($file, strlen(DIR_CATALOG . '/language/' . $this->config->get('config_language') . '/'));
			
			$data['files'][] = array(
				'code'  => $code,
				'path'  => $this->url->link('design/translation', 'token=' . $this->session->data['token'] . '&path=' . urlencode($code), true),
				'total' => '', //$this->model_design_language->getTranslationTotalByCode($code),
				'edit'  => $this->url->link('design/translation/edit', 'token=' . $this->session->data['token'] . '&path=' . urlencode($code), true)
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_path'] = $this->language->get('column_path');
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = $this->language->get('text_edit');
		
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_value'] = $this->language->get('entry_value');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['value'])) {
			$data['error_value'] = $this->error['value'];
		} else {
			$data['error_value'] = '';
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

		$data['action'] = $this->url->link('design/translation/edit', 'token=' . $this->session->data['token'] . '&path=' . $this->request->get['path'] . $url, true);

		$data['cancel'] = $this->url->link('design/translation', 'token=' . $this->session->data['token'] . $url, true);

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['translations'])) {
			$data['translations'] = $this->request->post['translations'];
		} elseif (isset($this->request->get['file'])) {
			$data['translations'] = $this->model_design_translation->getTranslationsByCode($this->request->get['file']);
		} else {
			$data['translations'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['translation'])) {
			foreach ($this->request->post['translation'] as $key => $translation) {
				if ((utf8_strlen($language_image_description['title']) < 2) || (utf8_strlen($language_image_description['title']) > 64)) {
					$this->error['language_image'][$language_image_id][$language_id] = $this->language->get('error_title');
				}
			}
		}

		return !$this->error;
	}
	
	public function translation() {
		$json = array();

		$this->load->model('design/country');

		$country_info = $this->model_design_translation->getTranslation($this->request->get['translation_id']);

		if ($country_info) {
			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}