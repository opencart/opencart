<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class Theme
 *
 * Can be loaded using $this->load->controller('design/theme');
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

		$data['list'] = $this->load->controller('design/theme.getList');

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

		$this->response->setOutput($this->load->controller('design/theme.getList'));
	}

	/**
	 * Get List
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

		// Themes
		$this->load->model('design/theme');

		// Setting
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
				'store'      => ($result['store_id'] ? $store : $this->language->get('text_default')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('design/theme.form', 'user_token=' . $this->session->data['user_token'] . '&theme_id=' . $result['theme_id']),
				'delete'     => $this->url->link('design/theme.delete', 'user_token=' . $this->session->data['user_token'] . '&theme_id=' . $result['theme_id'])
			] + $result;
		}

		// Total Themes
		$theme_total = $this->model_design_theme->getTotalThemes();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $theme_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('design/theme.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($theme_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($theme_total - $this->config->get('config_pagination_admin'))) ? $theme_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $theme_total, ceil($theme_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('design/theme_list', $data);
	}

	/**
	 * Get Form
	 *
	 * @return void
	 */
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

		// Theme
		if (isset($this->request->get['theme_id'])) {
			$this->load->model('design/theme');

			$theme_info = $this->model_design_theme->getTheme((int)$this->request->get['theme_id']);
		}

		if (!empty($theme_info)) {
			$data['theme_id'] = $theme_info['theme_id'];
		} else {
			$data['theme_id'] = 0;
		}

		// Setting
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (!empty($theme_info)) {
			$data['store_id'] = $theme_info['store_id'];
		} else {
			$data['store_id'] = 0;
		}

		// We grab the files from the default template directory
		$files = [];

		$path = DIR_CATALOG . 'view/template/';

		$directory = [$path];

		while (count($directory) != 0) {
			$next = array_shift($directory);

			if (is_dir($next)) {
				foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
					$directory[] = $file;
				}
			}

			// Add the file to the files to be deleted array
			$files[] = $next;
		}

		sort($files);

		$data['templates'] = [];

		foreach ($files as $file) {
			if (is_file($file)) {
				$data['templates'][] = substr(substr($file, 0, strrpos($file, '.')), strlen($path));
			}
		}

		// We grab the files from the extension template directory
		$data['extensions'] = [];

		$extensions = glob(DIR_EXTENSION . '*', GLOB_ONLYDIR);

		foreach ($extensions as $extension) {
			$extension = basename($extension);

			$path = DIR_EXTENSION . $extension . '/catalog/view/template';

			$directory = [$path];

			$files = [];

			while (count($directory) != 0) {
				$next = array_shift($directory);

				if (is_dir($next)) {
					foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
						$directory[] = $file;
					}
				}

				// Add the file to the files to be deleted array
				$files[] = $next;
			}

			sort($files);

			foreach ($files as $file) {
				if (is_file($file)) {
					$data['extensions'][] = 'extension/' . $extension . substr(substr($file, 0, strrpos($file, '.')), strlen($path));
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
	 * Template
	 *
	 * @return void
	 */
	public function template(): void {
		$this->load->language('design/theme');

		$json = [];

		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		// Default template load
		if (substr($path, 0, 10) != 'extension/') {
			$directory = DIR_CATALOG . 'view/template';
			$file = $directory . '/' . $path . '.twig';
		} else {
			// Extension template load
			$part = explode('/', $path);

			$directory = DIR_EXTENSION . $part[1] . '/catalog/view/template';

			unset($part[0]);
			unset($part[1]);

			$file = $directory . '/' . implode('/', $part) . '.twig';
		}

		if (!is_file($file) || (substr(str_replace('\\', '/', realpath($file)), 0, strlen($directory)) != $directory)) {
			$json['code'] = '';
		}

		if (!$json) {
			$json['code'] = file_get_contents($file);
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

		$required = [
			'theme_id' => 0,
			'route'    => '',
			'code'     => '',
			'status'   => 0
		];

		$post_info = $this->request->post + $required;

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/template';
		$file = $directory . '/' . (string)$post_info['route'] . '.twig';

		if (!is_file($file) || (substr(str_replace('\\', '/', realpath($file)), 0, strlen($directory)) != $directory)) {
			$json['error'] = $this->language->get('error_file');
		}

		// Extension template load
		if (substr($post_info['route'], 0, 10) == 'extension/') {
			$part = explode('/', $post_info['route']);

			$directory = DIR_EXTENSION . $part[1] . '/catalog/view/template';

			unset($part[0]);
			unset($part[1]);

			$route = implode('/', $part);

			$file = $directory . '/' . $route . '.twig';

			if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen($directory)) != $directory) {
				$json['error'] = $this->language->get('error_file');
			}
		}

		if (!$json) {
			// Theme
			$this->load->model('design/theme');

			if (!$post_info['theme_id']) {
				$json['theme_id'] = $this->model_design_theme->addTheme($post_info);
			} else {
				$this->model_design_theme->editTheme($post_info['theme_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
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

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (isset($this->request->get['theme_id'])) {
			$selected[] = (int)$this->request->get['theme_id'];
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Theme
			$this->load->model('design/theme');

			foreach ($selected as $theme_id) {
				$this->model_design_theme->deleteTheme($theme_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
