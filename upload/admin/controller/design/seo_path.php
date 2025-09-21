<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class SEO Path
 *
 * @package Opencart\Admin\Controller\Design
 */
class SeoPath extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('design/seo_path');

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
			'href' => $this->url->link('design/seo_path', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/seo_path.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/seo_path.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_path', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('design/seo_path');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}

		$data['action'] = $this->url->link('design/seo_path.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// SEO
		$data['seo_paths'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/seo_path');

		$results = $this->model_design_seo_path->getSeoPaths($filter_data);

		foreach ($results as $result) {
			$data['seo_paths'][] = ['edit' => $this->url->link('design/seo_path.form', 'user_token=' . $this->session->data['user_token'] . '&seo_path_id=' . $result['seo_path_id'] . $url)] + $result;
		}

		$seo_path_total = $this->model_design_seo_path->getTotalSeoPaths();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $seo_path_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('design/seo_path.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($seo_path_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($seo_path_total - $this->config->get('config_pagination_admin'))) ? $seo_path_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $seo_path_total, ceil($seo_path_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('design/seo_path_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('design/seo_path');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['seo_path_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('design/seo_path', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/seo_path.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('design/seo_path', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['seo_path_id'])) {
			$this->load->model('design/seo_path');

			$seo_path_info = $this->model_design_seo_path->getSeoPath($this->request->get['seo_path_id']);
		}

		if (!empty($seo_path_info)) {
			$data['seo_path_id'] = $seo_path_info['seo_path_id'];
		} else {
			$data['seo_path_id'] = 0;
		}

		if (!empty($seo_path_info)) {
			$data['query_match'] = $seo_path_info['query_match'];
		} else {
			$data['query_match'] = '';
		}

		if (!empty($seo_path_info)) {
			$data['query_replace'] = $seo_path_info['query_replace'];
		} else {
			$data['query_replace'] = '';
		}

		if (!empty($seo_path_info)) {
			$data['path_match'] = $seo_path_info['path_match'];
		} else {
			$data['path_match'] = '';
		}

		if (!empty($seo_path_info)) {
			$data['path_replace'] = $seo_path_info['path_replace'];
		} else {
			$data['path_replace'] = '';
		}

		if (!empty($seo_path_info)) {
			$data['sort_order'] = $seo_path_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_path_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('design/seo_path');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/seo_path')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'seo_path_id'   => 0,
			'query_match'   => '',
			'query_replace' => '',
			'path_match'    => '',
			'path_replace'  => '',
			'sort_order'    => 0
		];

		$post_info = $this->request->post + $required;

		if (!$json) {
			// SEO
			if (!$post_info['seo_path_id']) {
				$json['seo_path_id'] = $this->model_design_seo_path->addSeoPath($post_info);
			} else {
				$this->model_design_seo_path->editSeoPath($post_info['seo_path_id'], $post_info);
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
		$this->load->language('design/seo_path');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/seo_path')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// SEO
			$this->load->model('design/seo_path');

			foreach ($selected as $seo_path_id) {
				$this->model_design_seo_path->deleteSeoPath($seo_path_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
