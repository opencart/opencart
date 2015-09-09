<?php
class ControllerFeedGoogleBase extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('feed/google_base');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_base', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_import'] = $this->language->get('text_import');

		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_google_category'] = $this->language->get('entry_google_category');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_import'] = $this->language->get('button_import');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_category_add'] = $this->language->get('button_category_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_feed'),
			'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('feed/google_base', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('feed/google_base', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		$this->load->model('feed/google_base');
		$this->load->model('catalog/category');

		if (isset($this->request->post['google_base_category'])) {
			$google_base_categories = $this->request->post['google_base_category'];
		} elseif ($this->config->has('google_base_category')) {
			$google_base_categories = $this->config->get('google_base_category');
		} else {
			$google_base_categories = array();
		}

		$data['google_base_categories'] = array();

		foreach ($google_base_categories as $category_id => $google_base_category_id) {
			$google_base_info = $this->model_feed_google_base->getGoogleBaseCategory($google_base_category_id);

			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info && $google_base_info) {
				$data['google_base_categories'][] = array(
					'category_id'                => $category_info['category_id'],
					'category_name'              => $category_info['name'],
					'google_base_category_id'    => $google_base_info['google_base_category_id'],
					'google_base_category_name'  => $google_base_info['name']
				);
			}
		}

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/google_base';

		if (isset($this->request->post['google_base_status'])) {
			$data['google_base_status'] = $this->request->post['google_base_status'];
		} else {
			$data['google_base_status'] = $this->config->get('google_base_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/google_base.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'feed/google_base')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('feed/google_base');

		$this->model_feed_google_base->install();
	}

	public function uninstall() {
		$this->load->model('feed/google_base');

		$this->model_feed_google_base->uninstall();
	}

	/*
	https://support.google.com/merchants/answer/160081?hl=en

	Choose Taxonomy with numeric IDs in Plain Text (.txt)
	*/
	public function import() {
		$this->load->language('feed/google_base');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'feed/google_base')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				// Allowed file extension types
				if (utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)) != 'txt') {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				if ($this->request->files['file']['type'] != 'text/plain') {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$this->load->model('feed/google_base');

			// Get the contents of the uploaded file
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			$this->model_feed_google_base->import($content);

			unlink($this->request->files['file']['tmp_name']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('feed/google_base');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$filter_data = array(
				'filter_name' => $filter_name,
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_feed_google_base->getGoogleCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'google_base_category_id' => $result['google_base_category_id'],
					'name'                    => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
