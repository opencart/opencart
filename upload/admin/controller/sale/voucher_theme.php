<?php
namespace Opencart\Application\Controller\Sale;
class VoucherTheme extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('sale/voucher_theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher_theme');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/voucher_theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher_theme');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher_theme->addVoucherTheme($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/voucher_theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher_theme');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher_theme->editVoucherTheme($this->request->get['voucher_theme_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/voucher_theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher_theme');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $voucher_theme_id) {
				$this->model_sale_voucher_theme->deleteVoucherTheme($voucher_theme_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'vtd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('sale/voucher_theme/add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/voucher_theme/delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['voucher_themes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination'),
			'limit' => $this->config->get('config_pagination')
		];

		$voucher_theme_total = $this->model_sale_voucher_theme->getTotalVoucherThemes();

		$results = $this->model_sale_voucher_theme->getVoucherThemes($filter_data);

		foreach ($results as $result) {
			$data['voucher_themes'][] = [
				'voucher_theme_id' => $result['voucher_theme_id'],
				'name'             => $result['name'],
				'edit'             => $this->url->link('sale/voucher_theme/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_theme_id=' . $result['voucher_theme_id'] . $url)
			];
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

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
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($voucher_theme_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($voucher_theme_total - $this->config->get('config_pagination'))) ? $voucher_theme_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $voucher_theme_total, ceil($voucher_theme_total / $this->config->get('config_pagination')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_theme_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['voucher_theme_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = [];
		}

		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = '';
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['voucher_theme_id'])) {
			$data['action'] = $this->url->link('sale/voucher_theme/add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('sale/voucher_theme/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_theme_id=' . $this->request->get['voucher_theme_id'] . $url);
		}

		$data['cancel'] = $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['voucher_theme_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($this->request->get['voucher_theme_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['voucher_theme_description'])) {
			$data['voucher_theme_description'] = $this->request->post['voucher_theme_description'];
		} elseif (!empty($voucher_theme_info)) {
			$data['voucher_theme_description'] = $this->model_sale_voucher_theme->getDescriptions($this->request->get['voucher_theme_id']);
		} else {
			$data['voucher_theme_description'] = [];
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($voucher_theme_info)) {
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_theme_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['voucher_theme_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (!$this->request->post['image']) {
			$this->error['image'] = $this->language->get('error_image');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/voucher');

		foreach ($this->request->post['selected'] as $voucher_theme_id) {
			$voucher_total = $this->model_sale_voucher->getTotalVouchersByVoucherThemeId($voucher_theme_id);

			if ($voucher_total) {
				$this->error['warning'] = sprintf($this->language->get('error_voucher'), $voucher_total);
			}
		}

		return !$this->error;
	}
}