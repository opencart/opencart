<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class Theme
 *
 * @package Opencart\Admin\Controller\Design
 */
class Theme extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('design/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/theme', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/theme.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/theme.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->controller_design_theme->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('design/theme');

		$this->response->setOutput($this->controller_design_theme->getList());
	}

	/**
	 * getList
	 *
	 * @return string
	 */
	public function getList(): string {
		$this->load->language('design/theme');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('design/theme.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['themes'] = [];

		$this->load->model('design/theme');
		$this->load->model('setting/store');

		$results = $this->model_design_theme->getThemes(($page - 1) * $this->config->get('config_pagination_admin'), $this->config->get('config_pagination_admin'));

		foreach ($results as $result) {
			$store_info = $this->model_setting_store->getStore($result['store_id']);

			if ($store_info) {
				$store = $store_info['name'];
			} else {
				$store = '';
			}

			$data['themes'][] = [
				'theme_id'   => $result['theme_id'],
				'store'      => ($result['store_id'] ? $store : $this->language->get('text_default')),
				'route'      => $result['route'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('design/theme.form', 'user_token=' . $this->session->data['user_token'] . '&theme_id=' . $result['theme_id']),
				'delete'     => $this->url->link('design/theme.delete', 'user_token=' . $this->session->data['user_token'] . '&theme_id=' . $result['theme_id'])
			];
		}

		$theme_total = $this->model_design_theme->getTotalThemes();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $theme_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('design/theme.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($theme_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($theme_total - $this->config->get('config_pagination_admin'))) ? $theme_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $theme_total, ceil($theme_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('design/theme_list', $data);
	}

	public function form(): void {
		$this->load->language('design/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['theme_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

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
			'href' => $this->url->link('design/theme', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/theme.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('design/theme', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['theme_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$this->load->model('design/theme');

			$theme_info = $this->model_design_theme->getTheme($this->request->get['theme_id']);
		}

		if (isset($this->request->get['theme_id'])) {
			$data['theme_id'] = (int)$this->request->get['theme_id'];
		} else {
			$data['theme_id'] = 0;
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (!empty($theme_info)) {
			$data['store_id'] = $theme_info['store_id'];
		} else {
			$data['store_id'] = 0;
		}

		$files = [];

		// We grab the files from the default template directory
		$directory = [DIR_CATALOG . 'view/template/'];

		// While the path array is still populated keep looping through
		while (count($directory) != 0) {
			$next = array_shift($directory);

			if (is_dir($next)) {
				foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
					// If directory add to path array
					$directory[] = $file;
				}
			}

			// Add the file to the files to be deleted array
			$files[] = $next;
		}

		sort($files);

		foreach ($files as $file) {


			$data['directory'][] = [
				'name' => basename($file),
				'path' => trim($path . '/' . basename($file), '/')
			];
		}


		$directories = glob(DIR_EXTENSION . '*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$json['directory']['directory'][] = [
				'name' => basename($directory),
				'path' => 'extension/' . basename($directory)
			];
		}




		print_r($data['files']);


		if (!$path) {
			$json['directory'][] = [
				'name' => $this->language->get('text_extension'),
				'path' => 'extension',
			];
		}

		// Extension templates
		$json['extension'] = [];

		// List all the extensions
		if ($path == 'extension') {
			$directories = glob(DIR_EXTENSION . '*', GLOB_ONLYDIR);

			foreach ($directories as $directory) {
				$json['extension']['directory'][] = [
					'name' => basename($directory),
					'path' => 'extension/' . basename($directory)
				];
			}
		}

		// List extension sub directories directories
		if (substr($path, 0, 10) == 'extension/') {
			$route = '';

			$part = explode('/', $path);

			$extension = $part[1];

			unset($part[0]);
			unset($part[1]);

			if (isset($part[2])) {
				$route = implode('/', $part);
			}

			$safe = true;

			if (substr(str_replace('\\', '/', realpath(DIR_EXTENSION . $extension)), 0, strlen(DIR_EXTENSION)) != DIR_EXTENSION) {
				$safe = false;
			}

			$directory = DIR_EXTENSION . $extension . '/catalog/view/template';

			if (substr(str_replace('\\', '/', realpath($directory . '/' . $route)), 0, strlen($directory)) != $directory) {
				$safe = false;
			}

			if ($safe) {
				$files = glob(rtrim(DIR_EXTENSION . $extension . '/catalog/view/template/' . $route, '/') . '/*');

				sort($files);

				foreach ($files as $file) {
					if (is_dir($file)) {
						$json['extension']['directory'][] = [
							'name' => basename($file),
							'path' => $path . '/' . basename($file)
						];
					}

					if (is_file($file)) {
						$json['extension']['file'][] = [
							'name' => basename($file),
							'path' => $path . '/' . basename($file)
						];
					}
				}
			}
		}



		if (!empty($theme_info)) {
			$data['route'] = $theme_info['route'];
		} else {
			$data['route'] = '';
		}

		if (!empty($theme_info)) {
			$data['code'] = $theme_info['code'];
		} else {
			$data['code'] = '';
		}

		if (!empty($theme_info)) {
			$data['status'] = $theme_info['status'];
		} else {
			$data['status'] = 1;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme_form', $data));
	}

	/**
	 * Path
	 *
	 * @return void
	 */
	public function path(): void {
		$this->load->language('design/theme');

		$json = [];

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		// Default templates
		$json['directory'] = [];
		$json['file'] = [];

		$directory = DIR_CATALOG . 'view/template';

		if (substr(str_replace('\\', '/', realpath($directory . '/' . $path)), 0, strlen($directory)) == $directory) {
			// We grab the files from the default template directory
			$files = glob(rtrim(DIR_CATALOG . 'view/template/' . $path, '/') . '/*');

			foreach ($files as $file) {


				if (is_dir($file)) {
					$json['directory'][] = [
						'name' => basename($file),
						'path' => trim($path . '/' . basename($file), '/')
					];
				}

				if (is_file($file)) {
					$json['file'][] = [
						'name' => basename($file),
						'path' => trim($path . '/' . basename($file), '/')
					];
				}
			}
		}

		if (!$path) {
			$json['directory'][] = [
				'name' => $this->language->get('text_extension'),
				'path' => 'extension',
			];
		}

		// Extension templates
		$json['extension'] = [];

		// List all the extensions
		if ($path == 'extension') {
			$directories = glob(DIR_EXTENSION . '*', GLOB_ONLYDIR);

			foreach ($directories as $directory) {
				$json['extension']['directory'][] = [
					'name' => basename($directory),
					'path' => 'extension/' . basename($directory)
				];
			}
		}

		// List extension sub directories directories
		if (substr($path, 0, 10) == 'extension/') {
			$route = '';

			$part = explode('/', $path);

			$extension = $part[1];

			unset($part[0]);
			unset($part[1]);

			if (isset($part[2])) {
				$route = implode('/', $part);
			}

			$safe = true;

			if (substr(str_replace('\\', '/', realpath(DIR_EXTENSION . $extension)), 0, strlen(DIR_EXTENSION)) != DIR_EXTENSION) {
				$safe = false;
			}

			$directory = DIR_EXTENSION . $extension . '/catalog/view/template';

			if (substr(str_replace('\\', '/', realpath($directory . '/' . $route)), 0, strlen($directory)) != $directory) {
				$safe = false;
			}

			if ($safe) {
				$files = glob(rtrim(DIR_EXTENSION . $extension . '/catalog/view/template/' . $route, '/') . '/*');

				sort($files);

				foreach ($files as $file) {
					if (is_dir($file)) {
						$json['extension']['directory'][] = [
							'name' => basename($file),
							'path' => $path . '/' . basename($file)
						];
					}

					if (is_file($file)) {
						$json['extension']['file'][] = [
							'name' => basename($file),
							'path' => $path . '/' . basename($file)
						];
					}
				}
			}
		}

		if ($path) {
			$json['back'] = [
				'name' => $this->language->get('button_back'),
				'path' => urlencode(substr($path, 0, strrpos($path, '/'))),
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Template
	 *
	 * @return void
	 */
	public function template(): void {
		$this->load->language('design/theme');

		$json = [];

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		// Default template load
		$directory = DIR_CATALOG . 'view/template';

		if (is_file($directory . '/' . $path) && (substr(str_replace('\\', '/', realpath($directory . '/' . $path)), 0, strlen($directory)) == $directory)) {
			$json['code'] = file_get_contents(DIR_CATALOG . 'view/template/' . $path);
		}

		// Extension template load
		if (substr($path, 0, 10) == 'extension/') {
			$part = explode('/', $path);

			$extension = $part[1];

			unset($part[0]);
			unset($part[1]);

			$route = implode('/', $part);

			$safe = true;

			if (substr(str_replace('\\', '/', realpath(DIR_EXTENSION . $extension)), 0, strlen(DIR_EXTENSION)) != DIR_EXTENSION) {
				$safe = false;
			}

			$directory = DIR_EXTENSION . $extension . '/catalog/view/template';

			if (substr(str_replace('\\', '/', realpath($directory . '/' . $route)), 0, strlen($directory)) != $directory) {
				$safe = false;
			}

			if ($safe && is_file($directory . '/' . $route)) {
				$json['code'] = file_get_contents($directory . '/' . $route);
			}
		}

		// Custom template load
		$this->load->model('design/theme');

		$theme_info = $this->model_design_theme->getTheme($store_id, $path);

		if ($theme_info) {
			$json['code'] = html_entity_decode($theme_info['code']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('design/theme');

		$json = [];

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (substr($path, -5) != '.twig') {
			$json['error'] = $this->language->get('error_twig');
		}

		if (!$json) {
			$this->load->model('design/theme');

			$pos = strpos($path, '.');

			$this->model_design_theme->editTheme($store_id, ($pos !== false) ? substr($path, 0, $pos) : $path, $this->request->post['code']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reset
	 *
	 * @return void
	 */
	public function reset(): void {
		$json = [];

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		$directory = DIR_CATALOG . 'view/template';

		if (is_file($directory . '/' . $path) && (substr(str_replace('\\', '/', realpath($directory . '/' . $path)), 0, strlen($directory)) == $directory)) {
			$json['code'] = file_get_contents(DIR_CATALOG . 'view/template/' . $path);
		}

		// Extension template load
		if (substr($path, 0, 10) == 'extension/') {
			$part = explode('/', $path);

			$extension = $part[1];

			unset($part[0]);
			unset($part[1]);

			$route = implode('/', $part);

			$safe = true;

			if (substr(str_replace('\\', '/', realpath(DIR_EXTENSION . $extension)), 0, strlen(DIR_EXTENSION)) != DIR_EXTENSION) {
				$safe = false;
			}

			$directory = DIR_EXTENSION . $extension . '/catalog/view/template';

			if (substr(str_replace('\\', '/', realpath($directory . '/' . $route)), 0, strlen($directory)) != $directory) {
				$safe = false;
			}

			if ($safe && is_file($directory . '/' . $route)) {
				$json['code'] = file_get_contents($directory . '/' . $route);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('design/theme');

		$json = [];

		if (isset($this->request->get['theme_id'])) {
			$theme_id = (int)$this->request->get['theme_id'];
		} else {
			$theme_id = 0;
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/theme');

			$this->model_design_theme->deleteTheme($theme_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
