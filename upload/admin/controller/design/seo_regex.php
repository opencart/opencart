<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class SEO Regex
 *
 * @package Opencart\Admin\Controller\Design
 */
class SeoRegex extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('design/seo_regex');

		$this->document->setTitle($this->language->get('heading_title'));

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
			'href' => $this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/seo_regex.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/seo_regex.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_regex', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('design/seo_regex');

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
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('design/seo_regex.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// SEO
		$data['seo_regexs'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/seo_regex');

		$results = $this->model_design_seo_regex->getSeoRegexes($filter_data);

		foreach ($results as $result) {
			$data['seo_regexs'][] = ['edit' => $this->url->link('design/seo_regex.form', 'user_token=' . $this->session->data['user_token'] . '&seo_regex_id=' . $result['seo_regex_id'] . $url)] + $result;
		}

		$seo_regex_total = $this->model_design_seo_regex->getTotalSeoRegexes();

		// Pagination
		$data['total'] = $seo_regex_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('design/seo_regex.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($seo_regex_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($seo_regex_total - $this->config->get('config_pagination_admin'))) ? $seo_regex_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $seo_regex_total, ceil($seo_regex_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('design/seo_regex_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('design/seo_regex');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['seo_regex_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/seo_regex.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['seo_regex_id'])) {
			$this->load->model('design/seo_regex');

			$seo_regex_info = $this->model_design_seo_regex->getSeoRegex($this->request->get['seo_regex_id']);
		}

		if (!empty($seo_regex_info)) {
			$data['seo_regex_id'] = $seo_regex_info['seo_regex_id'];
		} else {
			$data['seo_regex_id'] = 0;
		}

		if (!empty($seo_regex_info)) {
			$data['key'] = $seo_regex_info['key'];
		} else {
			$data['key'] = '';
		}

		if (!empty($seo_regex_info)) {
			$data['match'] = $seo_regex_info['match'];
		} else {
			$data['match'] = '';
		}

		if (!empty($seo_regex_info)) {
			$data['replace'] = $seo_regex_info['replace'];
		} else {
			$data['replace'] = '';
		}

		if (!empty($seo_regex_info)) {
			$data['keyword'] = $seo_regex_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (!empty($seo_regex_info)) {
			$data['value'] = $seo_regex_info['value'];
		} else {
			$data['value'] = '';
		}

		if (!empty($seo_regex_info)) {
			$data['sort_order'] = $seo_regex_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_regex_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('design/seo_regex');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/seo_regex')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'seo_regex_id' => 0,
			'key'          => '',
			'match'        => '',
			'replace'      => '',
			'keyword'      => '',
			'value'        => '',
			'sort_order'   => 0
		];

		$post_info = $this->request->post + $required;

		if (!$json) {
			$this->load->model('design/seo_regex');

			if (!$post_info['seo_regex_id']) {
				$json['seo_regex_id'] = $this->model_design_seo_regex->addSeoRegex($post_info);
			} else {
				$this->model_design_seo_regex->editSeoRegex($post_info['seo_regex_id'], $post_info);
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
		$this->load->language('design/seo_regex');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/seo_regex')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// SEO
			$this->load->model('design/seo_regex');

			foreach ($selected as $seo_regex_id) {
				$this->model_design_seo_regex->deleteSeoRegex($seo_regex_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
