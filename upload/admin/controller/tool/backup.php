<?php
namespace Opencart\Admin\Controller\Tool;
/**
 * Class Backup
 *
 * @package Opencart\Admin\Controller\Tool
 */
class Backup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('tool/backup');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token'])
		];

		$data['upload'] = $this->url->link('tool/backup.upload', 'user_token=' . $this->session->data['user_token']);

		// Use the ini_get('upload_max_filesize') for the max file size
		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), ini_get('upload_max_filesize'));

		$data['config_file_max_size'] = ((int)preg_filter('/[^0-9]/', '', ini_get('upload_max_filesize')) * 1024 * 1024);

		$ignore = [
			DB_PREFIX . 'task',
			DB_PREFIX . 'user',
			DB_PREFIX . 'user_authorize',
			DB_PREFIX . 'user_group',
			DB_PREFIX . 'user_login',
			DB_PREFIX . 'user_token'
		];

		$data['tables'] = [];

		$this->load->model('tool/backup');

		$results = $this->model_tool_backup->getTables();

		foreach ($results as $result) {
			if (!in_array($result, $ignore)) {
				$data['tables'][] = $result;
			}
		}

		$data['history'] = $this->getHistory();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/backup', $data));
	}

	/**
	 * History
	 *
	 * @return void
	 */
	public function history(): void {
		$this->load->language('tool/backup');

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getHistory(): string {
		$this->load->language('tool/backup');

		$data['histories'] = [];

		$files = oc_directory_read(DIR_STORAGE . 'backup/', false, '/.+\.sql$/');

		foreach ($files as $file) {
			$size = filesize($file);

			$i = 0;

			$suffix = [
				'B',
				'KB',
				'MB',
				'GB',
				'TB',
				'PB',
				'EB',
				'ZB',
				'YB'
			];

			while (($size / 1024) > 1) {
				$size /= 1024;

				$i++;
			}

			$data['histories'][] = [
				'filename'   => basename($file),
				'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
				'date_added' => date($this->language->get('datetime_format'), filemtime($file)),
				'download'   => $this->url->link('tool/backup.download', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode(basename($file))),
			];
		}

		return $this->load->view('tool/backup_history', $data);
	}

	/**
	 * Backup
	 *
	 * @return void
	 */
	public function backup(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$required = ['backup' => []];

		$post_info = $this->request->post + $required;

		if (isset($post_info['backup'])) {
			$backup = (array)$post_info['backup'];
		} else {
			$backup = [];
		}

		if ($backup) {
			$disallowed = [
				DB_PREFIX . 'task',
				DB_PREFIX . 'user',
				DB_PREFIX . 'user_authorize',
				DB_PREFIX . 'user_group',
				DB_PREFIX . 'user_login',
				DB_PREFIX . 'user_token'
			];

			foreach ($backup as $table) {
				if (!str_starts_with($table, DB_PREFIX) || in_array($table, $disallowed)) {
					$json['error'] = sprintf($this->language->get('error_table'), $table);

					break;
				}
			}
		} else {
			$json['error'] = $this->language->get('error_backup');
		}

		if (!$json) {
			$task_data = [
				'code'   => 'backup',
				'action' => 'task/system/backup',
				'args'   => [
					'filename' => date('Y-m-d H.i.s') . '.sql',
					'backup'   => $backup
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Restore
	 *
	 * @return void
	 */
	public function restore(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$task_data = [
				'code'   => 'restore',
				'action' => 'task/system/restore',
				'args'   => ['filename' => $filename]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Upload
	 *
	 * @return void
	 */
	public function upload(): void {
		$this->load->language('tool/backup');

		$json = [];

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (empty($this->request->files['upload']['name']) || !is_file($this->request->files['upload']['tmp_name'])) {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			// Sanitize the filename
			$filename = basename(html_entity_decode($this->request->files['upload']['name'], ENT_QUOTES, 'UTF-8'));

			if (!oc_validate_length($filename, 5, 128)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			if (str_ends_with(strtolower($filename), '.sql')) {
				$json['error'] = $this->language->get('error_file_type');
			}
		}

		if (!$json) {
			move_uploaded_file($this->request->files['upload']['tmp_name'], DIR_STORAGE . 'backup/' . $filename);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Download
	 *
	 * @return void
	 */
	public function download(): void {
		$this->load->language('tool/backup');

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->redirect($this->url->link('error/permission', 'user_token=' . $this->session->data['user_token'], true));
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$this->response->redirect($this->url->link('error/not_found', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (!headers_sent()) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));

			if (ob_get_level()) {
				ob_end_clean();
			}

			readfile($file);

			exit();
		} else {
			exit($this->language->get('error_headers_sent'));
		}
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			unlink($file);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
