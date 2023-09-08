<?php
namespace Opencart\Admin\Controller\Sale;
/**
 * Class Voucher Theme
 *
 * @package Opencart\Admin\Controller\Sale
 */
class VoucherTheme extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('sale/voucher_theme');

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
			'href' => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('sale/voucher_theme.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/voucher_theme.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_theme', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('sale/voucher_theme');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'vtd.name';
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
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('sale/voucher_theme.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['voucher_themes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('sale/voucher_theme');

		$voucher_theme_total = $this->model_sale_voucher_theme->getTotalVoucherThemes();

		$results = $this->model_sale_voucher_theme->getVoucherThemes($filter_data);

		foreach ($results as $result) {
			$data['voucher_themes'][] = [
				'voucher_theme_id' => $result['voucher_theme_id'],
				'name'             => $result['name'],
				'edit'             => $this->url->link('sale/voucher_theme.form', 'user_token=' . $this->session->data['user_token'] . '&voucher_theme_id=' . $result['voucher_theme_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('sale/voucher_theme.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $voucher_theme_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('sale/voucher_theme.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($voucher_theme_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($voucher_theme_total - $this->config->get('config_pagination_admin'))) ? $voucher_theme_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $voucher_theme_total, ceil($voucher_theme_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('sale/voucher_theme_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('sale/voucher_theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['voucher_theme_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('sale/voucher_theme.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['voucher_theme_id'])) {
			$this->load->model('sale/voucher_theme');

			$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($this->request->get['voucher_theme_id']);
		}

		if (isset($this->request->get['voucher_theme_id'])) {
			$data['voucher_theme_id'] = (int)$this->request->get['voucher_theme_id'];
		} else {
			$data['voucher_theme_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($voucher_theme_info)) {
			$data['voucher_theme_description'] = $this->model_sale_voucher_theme->getDescriptions($this->request->get['voucher_theme_id']);
		} else {
			$data['voucher_theme_description'] = [];
		}

		if (!empty($voucher_theme_info)) {
			$data['image'] = $voucher_theme_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_theme_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('sale/voucher_theme');

		$json = [];

		if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['voucher_theme_description'] as $language_id => $value) {
			if ((oc_strlen($value['name']) < 3) || (oc_strlen($value['name']) > 32)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (!$this->request->post['image']) {
			$json['error']['image'] = $this->language->get('error_image');
		}

		if (!$json) {
			$this->load->model('sale/voucher_theme');

			if (!$this->request->post['voucher_theme_id']) {
				$json['voucher_theme_id'] = $this->model_sale_voucher_theme->addVoucherTheme($this->request->post);
			} else {
				$this->model_sale_voucher_theme->editVoucherTheme($this->request->post['voucher_theme_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('sale/voucher_theme');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/voucher');

		foreach ($selected as $voucher_theme_id) {
			$voucher_total = $this->model_sale_voucher->getTotalVouchersByVoucherThemeId($voucher_theme_id);

			if ($voucher_total) {
				$json['error'] = sprintf($this->language->get('error_voucher'), $voucher_total);
			}
		}

		if (!$json) {
			$this->load->model('sale/voucher_theme');

			foreach ($selected as $voucher_theme_id) {
				$this->model_sale_voucher_theme->deleteVoucherTheme($voucher_theme_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
