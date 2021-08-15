<?php
namespace Opencart\Admin\Controller\Design;
class SeoProfile extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('design/seo_profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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
			'href' => $this->url->link('design/seo_profile', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/seo_profile|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/seo_profile|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_profile', $data));
	}

	public function list(): void {
		$this->load->language('design/seo_profile');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . (string)$this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . (string)$this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}

		$data['action'] = $this->url->link('design/seo_profile|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['seo_profiles'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/seo_profile');

		$seo_profile_total = $this->model_design_seo_profile->getTotalSeoProfiles($filter_data);

		$results = $this->model_design_seo_profile->getSeoProfiles($filter_data);

		foreach ($results as $result) {
			$data['seo_profiles'][] = [
				'seo_profile_id' => $result['seo_profile_id'],
				'name'           => $result['name'],
				'key'            => $result['key'],
				'regex'          => $result['regex'],
				'sort_order'     => $result['sort_order'],
				'edit'           => $this->url->link('design/seo_profile|form', 'user_token=' . $this->session->data['user_token'] . '&seo_profile_id=' . $result['seo_profile_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('design/seo_profile|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_key'] = $this->url->link('design/seo_profile|list', 'user_token=' . $this->session->data['user_token'] . '&sort=key' . $url);
		$data['sort_regex'] = $this->url->link('design/seo_profile|list', 'user_token=' . $this->session->data['user_token'] . '&sort=regex' . $url);
		$data['sort_sort_order'] = $this->url->link('design/seo_profile|list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . (string)$this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . (string)$this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $seo_profile_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('design/seo_profile|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($seo_profile_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($seo_profile_total - $this->config->get('config_pagination_admin'))) ? $seo_profile_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $seo_profile_total, ceil($seo_profile_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('design/seo_profile_list', $data);
	}

	public function form(): void {
		$this->load->language('design/seo_profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['seo_profile_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . (string)$this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . (string)$this->request->get['order'];
		}

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
			'href' => $this->url->link('design/seo_profile', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/seo_profile|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('design/seo_profile', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['seo_profile_id'])) {
			$this->load->model('design/seo_profile');

			$seo_profile_info = $this->model_design_seo_profile->getSeoProfile($this->request->get['seo_profile_id']);
		}

		if (isset($this->request->get['seo_profile_id'])) {
			$data['seo_profile_id'] = (int)$this->request->get['seo_profile_id'];
		} else {
			$data['seo_profile_id'] = 0;
		}

		if (!empty($seo_profile_info)) {
			$data['name'] = $seo_profile_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($seo_profile_info)) {
			$data['key'] = $seo_profile_info['key'];
		} else {
			$data['key'] = '';
		}

		if (!empty($seo_profile_info)) {
			$data['regex'] = $seo_profile_info['regex'];
		} else {
			$data['regex'] = '';
		}

		if (!empty($seo_profile_info)) {
			$data['push'] = $seo_profile_info['push'];
		} else {
			$data['push'] = '';
		}

		if (!empty($seo_profile_info)) {
			$data['remove'] = $seo_profile_info['remove'];
		} else {
			$data['remove'] = '';
		}

		if (!empty($seo_profile_info)) {
			$data['sort_order'] = $seo_profile_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_profile_form', $data));
	}

	public function save(): void {
		$this->load->language('design/seo_profile');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/seo_profile')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['name']) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['key']) {
			$json['error']['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['regex'] || !preg_match('/^\/[\s\S]+\/$/', html_entity_decode($this->request->post['regex'], ENT_QUOTES, 'UTF-8'))) {
			$json['error']['regex'] = $this->language->get('error_regex');
		}

		if (!$json) {
			$this->load->model('design/seo_profile');

			if (!$this->request->post['seo_profile_id']) {
				$json['seo_profile_id'] = $this->model_design_seo_profile->addSeoProfile($this->request->post);
			} else {
				$this->model_design_seo_profile->editSeoProfile($this->request->post['seo_profile_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('design/seo_profile');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/seo_profile')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/seo_profile');

			foreach ($selected as $seo_profile_id) {
				$this->model_design_layout->deleteSeoProfile($seo_profile_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}