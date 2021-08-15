<?php
namespace Opencart\Admin\Controller\Common;
class Security extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/security');

		// Check install directory exists
		if (is_dir(DIR_CATALOG . '../install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		if (DIR_STORAGE == DIR_SYSTEM . 'storage/') {

		}

		$data['storage'] = DIR_SYSTEM . 'storage/';

		$path = '';

		$data['paths'] = [];

		$parts = explode('/', str_replace('\\', '/', rtrim(DIR_SYSTEM, '/')));

		foreach ($parts as $part) {
			$path .= $part . '/';

			$data['paths'][] = $path;
		}

		rsort($data['paths']);

		$data['document_root'] = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../') . '/');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('common/security', $data);

	}

	public function remove(): void {
		$this->load->language('common/security');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Make path into an array
			$source = [DIR_OPENCART . 'install/'];

			// While the path array is still populated keep looping through
			while (count($source) != 0) {
				$next = array_shift($source);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$source[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			rsort($files);

			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
