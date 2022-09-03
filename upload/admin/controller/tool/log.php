<?php
namespace Opencart\Admin\Controller\Tool;
class Log extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('tool/log');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/log', 'user_token=' . $this->session->data['user_token'])
		];

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		$file = DIR_LOGS . $this->config->get('config_error_filename');

		if (!is_file($file)) {
			file_put_contents($file, '', FILE_APPEND);
		}


		$data['log'] = [];

		$files = glob(DIR_LOGS . '*.log');

		foreach ($files as $file) {
			$error = '';

			$filename = basename($file);

			$size = filesize($file);

			if ($size >= 3145728) {
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

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$error = sprintf($this->language->get('error_size'), $filename, round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			}

			$handle = fopen($file, 'r+');

			$data['logs'][] = [
				'name'     => $filename,
				'output'   => fread($handle, 3145728),
				'download' => $this->url->link('tool/log.download', 'user_token=' . $this->session->data['user_token'] . '&filename=' . $filename),
				'clear'    => $this->url->link('tool/log.clear', 'user_token=' . $this->session->data['user_token'] . '&filename=' . $filename),
				'error'    => $error
			];

			fclose($handle);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/log', $data));
	}

	public function download(): void {
		$this->load->language('tool/log');

		if (isset($this->request->get['filename'])) {
			$filename = (string)basename($this->request->get['filename']);
		} else {
			$filename = '';
		}

		$file = DIR_LOGS . $filename;

		if (!is_file($file)) {
			$this->session->data['error'] = sprintf($this->language->get('error_file'), $filename);

			$this->response->redirect($this->url->link('tool/log', 'user_token=' . $this->session->data['user_token']));
		}

		if (!filesize($file)) {
			$this->session->data['error'] = sprintf($this->language->get('error_empty'), $filename);

			$this->response->redirect($this->url->link('tool/log', 'user_token=' . $this->session->data['user_token']));
		}

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/octet-stream');
		$this->response->addheader('Content-Disposition: attachment; filename="' . $this->config->get('config_name') . '_' . date('Y-m-d_H-i-s', time()) . '_error.log"');
		$this->response->addheader('Content-Transfer-Encoding: binary');

		$this->response->setOutput(file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
	}
	
	public function clear(): void {
		$this->load->language('tool/log');

		if (isset($this->request->get['filename'])) {
			$filename = (string)$this->request->get['filename'];
		} else {
			$filename = '';
		}

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/log')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_LOGS . $filename;

		if (!is_file($file)) {
			$json['error'] = sprintf($this->language->get('error_file'), $filename);
		}

		if (!$json) {
			$handle = fopen($file, 'w+');

			fclose($handle);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}