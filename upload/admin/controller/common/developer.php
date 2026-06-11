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

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'rebuild'  => $this->url->link('common/developer.rebuild', 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
			'template' => $this->url->link('common/developer.template', 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
			'sass'     => $this->url->link('common/developer.sass', 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
			'clear'    => $this->url->link('common/developer.clear', 'user_token=' . $this->session->data['user_token'] . '&store_id=0')
		];

		// Stores
		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name'],
				'rebuild'  => $this->url->link('common/developer.rebuild', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
				'template' => $this->url->link('common/developer.template', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
				'sass'     => $this->url->link('common/developer.sass', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
				'clear'    => $this->url->link('common/developer.clear', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id'])
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('common/developer', $data));
	}

	public function rebuild(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($this->request->get['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$this->request->get['store_id']);

			if (!$store_info) {
				$json['error'] = $this->language->get('error_store');
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_rebuild_success');

			$task_data = [
				'code'   => 'store.' . $store_info['store_id'],
				'action' => 'task/catalog/store',
				'args'   => ['store_id' => $store_info['store_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Theme
	 *
	 * @return void
	 */
	public function template(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directories = oc_directory_read(DIR_CACHE . 'template/');

			if ($directories) {
				foreach ($directories as $directory) {
					if (is_dir($directory)) {
						oc_directory_delete($directory);
					}
				}
			}

			$json['success'] = $this->language->get('text_theme_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * SASS Catalog
	 *
	 * Generate catalog SASS file.
	 *
	 * @return void
	 */
	public function sass(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_CATALOG . 'view/sass/stylesheet.scss';

		if (!is_file($file)) {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
		}

		if (!$json) {
			$task_data = [
				'code'   => 'sass',
				'action' => 'task/catalog/sass',
				'args'   => []
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_sass_catalog_success');
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

			$json['success'] = $this->language->get('text_html_success');
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
									foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
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
