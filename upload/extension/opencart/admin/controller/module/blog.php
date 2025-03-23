<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Module;
/**
 * Class Blog
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Module
 */
class Blog extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/module/blog');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/opencart/module/blog', 'user_token=' . $this->session->data['user_token'])
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/opencart/module/blog', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'])
			];
		}

		if (!isset($this->request->get['module_id'])) {
			$data['save'] = $this->url->link('extension/opencart/module/blog.save', 'user_token=' . $this->session->data['user_token']);
		} else {
			$data['save'] = $this->url->link('extension/opencart/module/blog.save', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id']);
		}

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		// Extension
		if (isset($this->request->get['module_id'])) {
			$this->load->model('setting/module');

			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($module_info['name'])) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($module_info['limit'])) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($module_info['width'])) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($module_info['height'])) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}

		if (isset($module_info['order'])) {
			$data['order'] = $module_info['order'];
		} else {
			$data['order'] = 'DESC';
		}

		if (isset($module_info['order_by'])) {
			$data['order_by'] = $module_info['order_by'];
		} else {
			$data['order_by'] = 'a.date_added';
		}

		if (isset($module_info['status'])) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->get['module_id'])) {
			$data['module_id'] = (int)$this->request->get['module_id'];
		} else {
			$data['module_id'] = 0;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/module/blog', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/module/blog');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/module/blog')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'module_id' => 0,
			'name'      => '',
			'width'     => 0,
			'height'    => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 3, 64)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$post_info['width']) {
			$json['error']['width'] = $this->language->get('error_width');
		}

		if (!$post_info['height']) {
			$json['error']['height'] = $this->language->get('error_height');
		}

		if (!$json) {
			// Extension
			$this->load->model('setting/module');

			if (!$post_info['module_id']) {
				$json['module_id'] = $this->model_setting_module->addModule('opencart.blog', $post_info);
			} else {
				$this->model_setting_module->editModule($post_info['module_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
