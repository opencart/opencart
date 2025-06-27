<?php
namespace Opencart\Admin\Controller\Tool;
/**
 * Class Menu
 *
 * Can be loaded using $this->load->controller('tool/menu');
 *
 * @package Opencart\Admin\Controller\Tool
 */
class Menu extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('tool/menu');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/menu', 'user_token=' . $this->session->data['user_token'])
		];

		$data['dropdown_add'] = $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=dropdown');
		$data['link_add'] = $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=link');
		$data['delete'] = $this->url->link('tool/menu.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->load->controller('tool/menu.getList');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/menu', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('tool/menu');

		$this->response->setOutput($this->load->controller('tool/menu.getList'));
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		$data['action'] = $this->url->link('tool/menu.list', 'user_token=' . $this->session->data['user_token']);

		$tree = [];

		$stack = [];

		$this->load->model('tool/menu');

		$results = $this->model_tool_menu->getMenus();

		foreach ($results as $result) {
			$result['children'] = [];

			$code   = $result['code'];
			$parent = $result['parent'];

			if (!array_key_exists($code, $tree)) {
				$tree[$code] = $result;
			} else {
				$tree[$code] = array_merge($result, $tree[$code]);
			}

			if (!array_key_exists($parent, $tree)) {
				$stack[$parent] = &$tree[$parent];
			}

			if (!array_key_exists($code, $tree)) {
				$tree[$code] = $result;
			}

			$tree[$parent]['children'][$code] = &$tree[$code];

			unset($stack[$code]);
		}

		unset($tree);

		$paths = [
			'catalog',
			'cms',
			'extension',
			'design',
			'sale',
			'customer',
			'marketing',
			'system',
			'report'
		];

		foreach ($paths as $path) {
			if (isset($stack[$path])) {
				$results = $stack[$path]['children'];

				// Level 1
				foreach ($results as $result) {
					$data['menus'][] = [
						'name' => $this->language->get('text_' . $path) . ' &gt ' . $result['name'],
						'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $result['type'] . '&menu_id=' . $result['menu_id'])
					] + $result;

					// Level 2
					foreach ($result['children'] as $children_1) {
						$data['menus'][] = [
							'name' => $this->language->get('text_' . $path) . ' &gt ' . $result['name'] . ' &gt ' . $children_1['name'],
							'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $children_1['type'] . '&menu_id=' . $children_1['menu_id'])
						] + $children_1;

						// Level 3
						foreach ($children_1['children'] as $children_2) {
							$data['menus'][] = [
								'name' => $this->language->get('text_' . $path) . ' &gt ' . $result['name'] . ' &gt ' . $children_1['name'] . ' &gt ' . $children_2['name'],
								'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $children_2['type'] . '&menu_id=' . $children_2['menu_id'])
							] + $children_2;

							// Level 4
							foreach ($children_2['children'] as $children_3) {
								$data['menus'][] = [
									'name' => $this->language->get('text_' . $path) . ' &gt ' . $result['name'] . ' &gt ' . $children_1['name'] . ' &gt ' . $children_2['name'] . ' &gt ' . $children_3['name'],
									'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $children_3['type'] . '&menu_id=' . $children_3['menu_id'])
								] + $children_3;
							}
						}
					}
				}

			}
		}

		return $this->load->view('tool/menu_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('tool/menu');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['menu_id'])) {
			$menu_id = (int)$this->request->get['menu_id'];
		} else {
			$menu_id = 0;
		}

		if (isset($this->request->get['type'])) {
			$type = (string)$this->request->get['type'];
		} else {
			$type = 'dropdown';
		}

		$data['text_form'] = !$menu_id ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/menu', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('tool/menu.save', 'user_token=' . $this->session->data['user_token'] . '&type=' . $type);
		$data['back'] = $this->url->link('tool/menu', 'user_token=' . $this->session->data['user_token']);

		// Menu
		$this->load->model('tool/menu');

		if (isset($this->request->get['menu_id'])) {
			$menu_info = $this->model_tool_menu->getMenu((int)$this->request->get['menu_id']);
		}

		if (!empty($menu_info)) {
			$data['menu_id'] = $menu_info['menu_id'];
		} else {
			$data['menu_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($menu_info)) {
			$data['menu_description'] = $this->model_tool_menu->getDescriptions($menu_info['menu_id']);
		} else {
			$data['menu_description'] = [];
		}

		if (!empty($menu_info)) {
			$data['code'] = $menu_info['code'];
		} else {
			$data['code'] = '';
		}

		$data['type'] = $type;

		if (!empty($menu_info)) {
			$data['route'] = $menu_info['route'];
		} else {
			$data['route'] = '';
		}

		// Build Tree
		$recursion = function($data = [], $parent = '') use (&$recursion) {
			$menu_data = [];

			if (isset($data[$parent])) {
				foreach ($data[$parent]['children'] as $child) {
					$menu_data[$child['code']] = $child + ['children' => $recursion($data, $child['code'])];
				}
			}

			return $menu_data;
		};

		$data['menus'] = [];

		$paths = [
			'catalog',
			'cms',
			'extension',
			'design',
			'sale',
			'customer',
			'marketing',
			'system',
			'report'
		];

		$menus = [];

		$results = $this->model_tool_menu->getMenus();


		foreach ($test as $result) {
			foreach ($result['children'] as $children_1) {
				$data['menus'][] = [
						'name' => $name . ' &gt ' . $result['name'] . ' &gt ' . $children_1['name'],
						'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $children_1['type'] . '&menu_id=' . $children_1['menu_id'])
					] + $children_1;

				foreach ($children_1['children'] as $children_2) {
					$data['menus'][] = [
							'name' => $name . ' &gt ' . $result['name'] . ' &gt ' . $children_1['name'] . ' &gt ' . $children_2['name'],
							'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $children_2['type'] . '&menu_id=' . $children_2['menu_id'])
						] + $children_2;

					foreach ($children_2['children'] as $children_3) {
						$data['menus'][] = [
								'name' => $name . ' &gt ' . $result['name'] . ' &gt ' . $children_1['name'] . ' &gt ' . $children_2['name'] . ' &gt ' . $children_3['name'],
								'edit' => $this->url->link('tool/menu.form', 'user_token=' . $this->session->data['user_token'] . '&type=' . $children_3['type'] . '&menu_id=' . $children_3['menu_id'])
							] + $children_3;
					}
				}
			}
		}


		foreach ($paths as $path) {
			$data['menus'][$path] = [
				'name'     => $this->language->get('text_' . $path),
				'code'     => $path,
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => $recursion($results, $path)
			];
		}

		if (!empty($menu_info)) {
			$data['parent'] = $menu_info['parent'];
		} else {
			$data['parent'] = '';
		}

		if (!empty($menu_info)) {
			$data['sort_order'] = $menu_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/menu_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('tool/menu');

		if (isset($this->request->get['type'])) {
			$type = (string)$this->request->get['type'];
		} else {

			$type = 'dropdown';
		}

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/menu')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'menu_id'          => 0,
			'menu_description' => [],
			'code'             => '',
			'route'            => '',
			'path'             => ''
		];

		$post_info = $this->request->post + $required + ['type' => $type];

		foreach ((array)$post_info['menu_description'] as $language_id => $value) {
			if (!oc_validate_length((string)$value['name'], 1, 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (!$post_info['code']) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		$protect = [
			'catalog',
			'cms',
			'extension',
			'design',
			'sale',
			'customer',
			'marketing',
			'system',
			'report',
		];

		$this->load->model('tool/menu');

		$menu_info = $this->model_tool_menu->getMenuByCode($post_info['code']);

		if (in_array($post_info['code'], $protect) || ($menu_info && ($post_info['menu_id'] != $menu_info['menu_id']))) {
			$json['error']['code'] = $this->language->get('error_exists');
		}

		if ($type == 'link' && !$post_info['route']) {
			$json['error']['route'] = $this->language->get('error_route');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			if (!$post_info['menu_id']) {
				$json['menu_id'] = $this->model_tool_menu->addMenu($post_info);
			} else {
				$this->model_tool_menu->editMenu($post_info['menu_id'], $post_info);
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
		$this->load->language('tool/menu');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'tool/menu')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('tool/menu');

			foreach ($selected as $menu_id) {
				$this->model_tool_menu->deleteMenu($menu_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
