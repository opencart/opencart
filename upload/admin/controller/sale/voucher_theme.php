<?php
class ControllerSaleVoucherTheme extends Controller {
	private $error = array();

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

			$this->response->redirect($this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url, true));
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

			$this->response->redirect($this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url, true));
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

			$this->response->redirect($this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url, true));
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('sale/voucher_theme/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('sale/voucher_theme/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['voucher_themes'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$voucher_theme_total = $this->model_sale_voucher_theme->getTotalVoucherThemes();

		$results = $this->model_sale_voucher_theme->getVoucherThemes($filter_data);

		foreach ($results as $result) {
			$data['voucher_themes'][] = array(
				'voucher_theme_id' => $result['voucher_theme_id'],
				'name'             => $result['name'],
				'edit'             => $this->url->link('sale/voucher_theme/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_theme_id=' . $result['voucher_theme_id'] . $url, true)
			);
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
			$data['selected'] = array();
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

		$data['sort_name'] = $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $voucher_theme_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($voucher_theme_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($voucher_theme_total - $this->config->get('config_limit_admin'))) ? $voucher_theme_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $voucher_theme_total, ceil($voucher_theme_total / $this->config->get('config_limit_admin')));

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
			$data['error_name'] = array();
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['voucher_theme_id'])) {
			$data['action'] = $this->url->link('sale/voucher_theme/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('sale/voucher_theme/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_theme_id=' . $this->request->get['voucher_theme_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('sale/voucher_theme', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['voucher_theme_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($this->request->get['voucher_theme_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['voucher_theme_description'])) {
			$data['voucher_theme_description'] = $this->request->post['voucher_theme_description'];
		} elseif (isset($this->request->get['voucher_theme_id'])) {
			$data['voucher_theme_description'] = $this->model_sale_voucher_theme->getVoucherThemeDescriptions($this->request->get['voucher_theme_id']);
		} else {
			$data['voucher_theme_description'] = array();
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($voucher_theme_info)) {
			$data['image'] = $voucher_theme_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($voucher_theme_info) && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($voucher_theme_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

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