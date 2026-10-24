<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Developer
 *
 * Can be loaded using $this->load->controller('common/developer');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Developer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/developer');

		$data['run'] = $this->url->link('common/developer.run', 'user_token=' . $this->session->data['user_token']);
		$data['clear'] = $this->url->link('common/developer.clear', 'user_token=' . $this->session->data['user_token']);

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		// Stores
		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		$data['ssrs'] = [];

		$this->load->model('setting/ssr');

		$results = $this->model_setting_ssr->getSsrs();

		foreach ($results as $result) {
			$data['ssrs'][] = [
				'ssr_id' => $result['ssr_id'],
				'name'   => $this->language->get('text_' . $result['code'])
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('common/developer', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('common/developer');

		$this->response->setOutput($this->getList());
	}

	public function run(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['store_id'])) {
			$store_id = (int)$this->request->post['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		if ($store_id) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if (!$store_info) {
				$json['error'] = $this->language->get('error_store');
			}
		}

		if (!$json) {
			$this->load->model('setting/task');
			$this->load->model('setting/ssr');

			foreach ($selected as $ssr_id) {
				$ssr_info = $this->model_setting_ssr->getSsr((int)$ssr_id);

				if ($ssr_info) {
					$task_data = [
						'code'   => $ssr_info['code'] . '.' . $store_info['store_id'],
						'action' => $ssr_info['action'],
						'args'   => ['store_id' => $store_info['store_id']]
					];

					$this->model_setting_task->addTask($task_data);

					$this->model_setting_ssr->editSsr($ssr_info['ssr_id']);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Clear
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}





		if (!$json) {
			$files = oc_directory_read(DIR_CATALOG . 'view/html/');

			foreach ($files as $file) {
				oc_directory_delete($file);
			}

			$json['success'] = $this->language->get('text_clear_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Cache
	 *
	 * @return void
	 */
	public function cache(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = oc_directory_read(DIR_CACHE);

			foreach ($files as $file) {
				if (str_starts_with(basename($file), 'cache.') && is_file($file)) {
					oc_file_delete($file);
				}
			}

			$json['success'] = $this->language->get('text_cache_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Vendor
	 *
	 * Generate new autoloader file
	 *
	 * @return void
	 */
	public function vendor(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Generate php autoload file
			$code = '<?php' . "\n";

			$files = glob(DIR_STORAGE . 'vendor/*/*/composer.json');

			foreach ($files as $file) {
				$output = json_decode(file_get_contents($file), true);

				$code .= '// ' . $output['name'] . "\n";

				if (isset($output['autoload'])) {
					$directory = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));

					// Autoload psr-4 files
					if (isset($output['autoload']['psr-4'])) {
						$autoload = $output['autoload']['psr-4'];

						foreach ($autoload as $namespace => $path) {
							if (!is_array($path)) {
								$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
							} else {
								foreach ($path as $value) {
									$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($value, '/') . '/' . '\', true);' . "\n";
								}
							}
						}
					}

					// Autoload psr-0 files
					if (isset($output['autoload']['psr-0'])) {
						$autoload = $output['autoload']['psr-0'];

						foreach ($autoload as $namespace => $path) {
							if (!is_array($path)) {
								$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
							} else {
								foreach ($path as $value) {
									$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($value, '/') . '/' . '\', true);' . "\n";
								}
							}
						}
					}

					// Autoload classmap
					if (isset($output['autoload']['classmap'])) {
						$autoload = [];

						$classmaps = $output['autoload']['classmap'];

						foreach ($classmaps as $classmap) {
							$directories = [dirname($file) . '/' . $classmap];

							while (count($directories) != 0) {
								$next = array_shift($directories);

								if (is_dir($next)) {
									$files = scandir($next);

									if ($files !== false) {
										foreach ($files as $filename) {											
											if ($filename == '.' || $filename == '..') {
												continue;
											}

											$file = rtrim($next, '/') . '/' . $filename;

											if (is_dir($file)) {
												$directories[] = $file . '/';
											}

											if (is_file($file)) {
												$namespace = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/' . $directory . $classmap) + 1);

												if ($namespace) {
													$autoload[$namespace] = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));
												}
											}
										}
									}
								}
							}
						}

						foreach ($autoload as $namespace => $path) {
							$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
						}
					}

					// Autoload files
					if (isset($output['autoload']['files'])) {
						$files = $output['autoload']['files'];

						foreach ($files as $file) {
							$code .= 'if (is_file(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\')) {' . "\n";
							$code .= '	require_once(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\');' . "\n";
							$code .= '}' . "\n";
						}
					}
				}

				$code .= "\n";
			}

			file_put_contents(DIR_SYSTEM . 'vendor.php', trim($code));

			$json['success'] = $this->language->get('text_vendor_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
