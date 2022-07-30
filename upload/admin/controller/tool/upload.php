<?php
namespace Opencart\Admin\Controller\Tool;
use \Opencart\System\Helper as Helper;
class Upload extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('tool/upload');

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
			'href' => $this->url->link('tool/upload', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('tool/upload|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('tool/upload|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upload', $data));
	}

	public function list(): void {
		$this->load->language('tool/upload');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('tool/upload|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['uploads'] = [];

		$filter_data = [
			'filter_name'	   => $filter_name,
			'filter_date_from' => $filter_date_from,
			'filter_date_to'   => $filter_date_to,
			'sort'             => $sort,
			'order'            => $order,
			'start'            => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'            => $this->config->get('config_pagination_admin')
		];

		$this->load->model('tool/upload');

		$upload_total = $this->model_tool_upload->getTotalUploads($filter_data);

		$results = $this->model_tool_upload->getUploads($filter_data);

		foreach ($results as $result) {
			$data['uploads'][] = [
				'upload_id'  => $result['upload_id'],
				'name'       => $result['name'],
				'code'       => $result['code'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'download'   => $this->url->link('tool/upload|download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $result['code'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('tool/upload|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_code'] = $this->url->link('tool/upload|list', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url);
		$data['sort_date_added'] = $this->url->link('tool/upload|list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $upload_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('tool/upload|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($upload_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($upload_total - $this->config->get('config_pagination_admin'))) ? $upload_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $upload_total, ceil($upload_total / $this->config->get('config_pagination_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('tool/upload_list', $data);
	}

	public function delete(): void {
		$this->load->language('tool/upload');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'tool/upload')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('tool/upload');

			foreach ($selected as $upload_id) {
				// Remove file before deleting DB record.
				$upload_info = $this->model_tool_upload->getUpload($upload_id);

				if ($upload_info && is_file(DIR_UPLOAD . $upload_info['filename'])) {
					unlink(DIR_UPLOAD . $upload_info['filename']);
				}

				$this->model_tool_upload->deleteUpload($upload_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download(): void {
		$this->load->language('tool/upload');

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('tool/upload');

		$upload_info = $this->model_tool_upload->getUploadByCode($code);

		if ($upload_info) {
			$file = DIR_UPLOAD . $upload_info['filename'];
			$mask = basename($upload_info['name']);

			if (!headers_sent()) {
				if (is_file($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');
					exit;
				} else {
					exit(sprintf($this->language->get('error_not_found'), basename($file)));
				}
			} else {
				exit($this->language->get('error_headers_sent'));
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'user_token=' . $this->session->data['user_token'])
			];

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function upload(): void {
		$this->load->language('tool/upload');

		$json = [];

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/upload')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (empty($this->request->files['file']['name']) || !is_file($this->request->files['file']['tmp_name'])) {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			// Sanitize the filename
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

			// Validate the filename length
			if ((Helper\Utf8\strlen($filename) < 3) || (Helper\Utf8\strlen($filename) > 128)) {
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
				$json['error'] = $this->language->get('error_file_type');
			}

			// Allowed file mime types
			$allowed = [];

			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_file_type');
			}

			// Return any upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		}

		if (!$json) {
			$file = $filename . '.' . Helper\General\token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
