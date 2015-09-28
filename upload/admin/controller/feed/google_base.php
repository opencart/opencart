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

		$data['entry_google_category'] = $this->language->get('entry_google_category');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_import'] = $this->language->get('button_import');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_category_add'] = $this->language->get('button_category_add');

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

	public function category() {
		$this->load->language('feed/google_base');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_google_category'] = $this->language->get('column_google_category');
		$data['column_category'] = $this->language->get('column_category');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['google_base_categories'] = array();

		$this->load->model('feed/google_base');

		$results = $this->model_feed_google_base->getCategories(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['google_base_categories'][] = array(
				'google_base_category_id' => $result['google_base_category_id'],
				'google_base_category'    => $result['google_base_category'],
				'category_id'             => $result['category_id'],
				'category'                => $result['category']
			);
		}

		$category_total = $this->model_feed_google_base->getTotalCategories();

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('feed/google_base/category', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($category_total - 10)) ? $category_total : ((($page - 1) * 10) + 10), $category_total, ceil($category_total / 10));

		$this->response->setOutput($this->load->view('feed/google_base_category.tpl', $data));
	}

	public function addCategory() {
		$this->load->language('feed/google_base');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (!empty($this->request->post['google_base_category_id']) && !empty($this->request->post['category_id'])) {
			$this->load->model('feed/google_base');

			$this->model_feed_google_base->addCategory($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCategory() {
		$this->load->language('feed/google_base');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('feed/google_base');

			$this->model_feed_google_base->deleteCategory($this->request->post['category_id']);

			$json['success'] = $this->language->get('text_success');
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
				'filter_name' => html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'),
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_feed_google_base->getGoogleBaseCategories($filter_data);

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
