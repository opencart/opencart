<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Anti-Spam
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Antispam extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/antispam');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = (string)$this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		$allowed = [
			'filter_keyword',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('cms/antispam', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('cms/antispam.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/antispam.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['filter_keyword'] = $filter_keyword;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/antispam', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/antispam');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = (string)$this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_keyword',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('cms/antispam.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Anti-Spams
		$data['antispams'] = [];

		$filter_data = [
			'filter_keyword' => $filter_keyword,
			'start'          => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'          => $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/antispam');

		$results = $this->model_cms_antispam->getAntispams($filter_data);

		foreach ($results as $result) {
			$data['antispams'][] = ['edit' => $this->url->link('cms/antispam.form', 'user_token=' . $this->session->data['user_token'] . '&antispam_id=' . $result['antispam_id'] . $url)] + $result;
		}

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url = '&filter_keyword' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));
		}

		// Total Anti-Spams
		$antispam_total = $this->model_cms_antispam->getTotalAntispams($filter_data);

		$data['total'] = $antispam_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('cms/antispam.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($antispam_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($antispam_total - $this->config->get('config_pagination_admin'))) ? $antispam_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $antispam_total, ceil($antispam_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('cms/antispam_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('cms/antispam');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['antispam_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_keyword',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('cms/antispam', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('cms/antispam.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('cms/antispam', 'user_token=' . $this->session->data['user_token'] . $url);

		// Anti-spam
		if (isset($this->request->get['antispam_id'])) {
			$this->load->model('cms/antispam');

			$antispam_info = $this->model_cms_antispam->getAntispam($this->request->get['antispam_id']);
		}

		if (!empty($antispam_info)) {
			$data['antispam_id'] = $antispam_info['antispam_id'];
		} else {
			$data['antispam_id'] = 0;
		}

		if (!empty($antispam_info)) {
			$data['keyword'] = $antispam_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/antispam_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/antispam');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/antispam')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'antispam_id' => 0,
			'keyword'     => ''
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['keyword'], 1, 64)) {
			$json['error']['keyword'] = $this->language->get('error_keyword');
		}

		if (!$json) {
			// Anti-Spam
			$this->load->model('cms/antispam');

			if (!$post_info['antispam_id']) {
				$json['antispam_id'] = $this->model_cms_antispam->addAntispam($post_info);
			} else {
				$this->model_cms_antispam->editAntispam($post_info['antispam_id'], $post_info);
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
		$this->load->language('cms/antispam');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/antispam')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/antispam');

			foreach ($selected as $antispam_id) {
				$this->model_cms_antispam->deleteAntispam($antispam_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
