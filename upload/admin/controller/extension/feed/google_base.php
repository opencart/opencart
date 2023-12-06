<?php
class ControllerExtensionFeedGoogleBase extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/feed/google_base');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('feed_google_base', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/google_base', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/feed/google_base', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);

		$data['user_token'] = $this->session->data['user_token'];

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=extension/feed/google_base';

		if (isset($this->request->post['feed_google_base_status'])) {
			$data['feed_google_base_status'] = $this->request->post['feed_google_base_status'];
		} else {
			$data['feed_google_base_status'] = $this->config->get('feed_google_base_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/google_base', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/google_base')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/feed/google_base');

		$this->model_extension_feed_google_base->install();
	}

	public function uninstall() {
		$this->load->model('extension/feed/google_base');

		$this->model_extension_feed_google_base->uninstall();
	}

	public function import() {
		$this->load->language('extension/feed/google_base');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'extension/feed/google_base')) {
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

			$this->load->model('extension/feed/google_base');

			// Get the contents of the uploaded file
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			$this->model_extension_feed_google_base->import($content);

			unlink($this->request->files['file']['tmp_name']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function category() {
		$this->load->language('extension/feed/google_base');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['google_base_categories'] = array();

		$limit = 10;
		$filter_data = array(
			'start'       => ($page - 1) * $limit,
			'limit'       => $limit
		);
		
		$this->load->model('extension/feed/google_base');
		$results = $this->model_extension_feed_google_base->getCategories($filter_data);

		foreach ($results as $result) {
			$data['google_base_categories'][] = array(
				'google_base_category_id' => $result['google_base_category_id'],
				'google_base_category'    => $result['google_base_category'],
				'category_id'             => $result['category_id'],
				'category'                => $result['category']
			);
		}

		$category_total = $this->model_extension_feed_google_base->getTotalCategories();

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/feed/google_base/category', 'user_token=' . $this->session->data['user_token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($category_total - $limit)) ? $category_total : ((($page - 1) * $limit) + $limit), $category_total, ceil($category_total / $limit));

		$this->response->setOutput($this->load->view('extension/feed/google_base_category', $data));
	}

	public function addCategory() {
		$this->load->language('extension/feed/google_base');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/feed/google_base')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (!empty($this->request->post['google_base_category_id']) && !empty($this->request->post['category_id'])) {
			$this->load->model('extension/feed/google_base');

			$this->model_extension_feed_google_base->addCategory($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCategory() {
		$this->load->language('extension/feed/google_base');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/feed/google_base')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/feed/google_base');

			$this->model_extension_feed_google_base->deleteCategory($this->request->post['category_id']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/feed/google_base');

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

			$results = $this->model_extension_feed_google_base->getGoogleBaseCategories($filter_data);

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
