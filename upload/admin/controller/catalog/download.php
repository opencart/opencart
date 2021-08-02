<?php
namespace Opencart\Admin\Controller\Catalog;
class Download extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('catalog/download');

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/download', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/download|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/download|delete', 'user_token=' . $this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		$data['list'] = $this->getList();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/download', $data));
	}

	public function list(): void {
		$this->load->language('catalog/download');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'dd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

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

		$data['action'] = $this->url->link('catalog/download|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['downloads'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/download');

		$download_total = $this->model_catalog_download->getTotalDownloads();

		$results = $this->model_catalog_download->getDownloads($filter_data);

		foreach ($results as $result) {
			$data['downloads'][] = [
				'download_id' => $result['download_id'],
				'name'        => $result['name'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'        => $this->url->link('catalog/download|form', 'user_token=' . $this->session->data['user_token'] . '&download_id=' . $result['download_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/download|list', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.name' . $url);
		$data['sort_date_added'] = $this->url->link('catalog/download|list', 'user_token=' . $this->session->data['user_token'] . '&sort=d.date_added' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $download_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/download|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($download_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($download_total - $this->config->get('config_pagination_admin'))) ? $download_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $download_total, ceil($download_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/download_list', $data);
	}

	public function form(): void {
		$this->load->language('catalog/download');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['download_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = $this->config->get('config_file_max_size');

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/download', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/download|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('catalog/download', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['download_id'])) {
			$this->load->model('catalog/download');

			$download_info = $this->model_catalog_download->getDownload($this->request->get['download_id']);
		}

		if (isset($this->request->get['download_id'])) {
			$data['download_id'] = (int)$this->request->get['download_id'];
		} else {
			$data['download_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['download_id'])) {
			$data['download_description'] = $this->model_catalog_download->getDescriptions($this->request->get['download_id']);
		} else {
			$data['download_description'] = [];
		}

		if (!empty($download_info)) {
			$data['filename'] = $download_info['filename'];
		} else {
			$data['filename'] = '';
		}

		if (!empty($download_info)) {
			$data['mask'] = $download_info['mask'];
		} else {
			$data['mask'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/download_form', $data));
	}

	public function save(): void {
		$this->load->language('catalog/download');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/download')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['download_description'] as $language_id => $value) {
			if ((utf8_strlen(trim($value['name'])) < 3) || (utf8_strlen($value['name']) > 64)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen(trim($this->request->post['filename'])) < 3) || (utf8_strlen($this->request->post['filename']) > 128)) {
			$json['error']['filename'] = $this->language->get('error_filename');
		}

		if (!is_file(DIR_DOWNLOAD . $this->request->post['filename'])) {
			$json['error']['filename'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen(trim($this->request->post['mask'])) < 3) || (utf8_strlen($this->request->post['mask']) > 128)) {
			$json['error']['mask'] = $this->language->get('error_mask');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/download');

			if (!$this->request->post['download_id']) {
				$json['download_id'] = $this->model_catalog_download->addDownload($this->request->post);
			} else {
				$this->model_catalog_download->editDownload($this->request->post['download_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('catalog/download');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/download')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($selected as $download_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByDownloadId($download_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/download');

			foreach ($selected as $download_id) {
				$this->model_catalog_download->deleteDownload($download_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function report(): void {
		$this->load->language('catalog/download');

		if (isset($this->request->get['download_id'])) {
			$download_id = (int)$this->request->get['download_id'];
		} else {
			$download_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reports'] = [];

		$this->load->model('catalog/download');
		$this->load->model('customer/customer');
		$this->load->model('setting/store');

		$results = $this->model_catalog_download->getReports($download_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$store_info = $this->model_setting_store->getStore($result['store_id']);

			if ($store_info) {
				$store = $store_info['name'];
			} elseif (!$result['store_id']) {
				$store = $this->config->get('config_name');
			} else {
				$store = '';
			}

			$data['reports'][] = [
				'ip'         => $result['ip'],
				'account'    => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'store'      => $store,
				'country'    => $result['country'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&filter_ip=' . $result['ip'])
			];
		}

		$report_total = $this->model_catalog_download->getTotalReports($download_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $report_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/download|report', 'user_token=' . $this->session->data['user_token'] . '&download_id=' . $download_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($report_total - 10)) ? $report_total : ((($page - 1) * 10) + 10), $report_total, ceil($report_total / 10));

		$this->response->setOutput($this->load->view('catalog/download_report', $data));
	}

	public function upload(): void {
		$this->load->language('catalog/download');

		$json = [];

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'catalog/download')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}

				// Allowed file extension types
				$allowed = [];

				$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

				$filetypes = explode("\n", $extension_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				$allowed = [];

				$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

				$filetypes = explode("\n", $mime_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
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
			$file = $filename . '.' . token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

			$json['filename'] = $file;
			$json['mask'] = $filename;

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/download');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			];

			$results = $this->model_catalog_download->getDownloads($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'download_id' => $result['download_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
