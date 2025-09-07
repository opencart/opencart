<?php
class ControllerCatalogRecurring extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_recurring->addRecurring($this->request->post);

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

			$this->response->redirect($this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_recurring->editRecurring($this->request->get['recurring_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $recurring_id) {
				$this->model_catalog_recurring->deleteRecurring($recurring_id);
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

			$this->response->redirect($this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $recurring_id) {
				$this->model_catalog_recurring->copyRecurring($recurring_id);
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

			$this->response->redirect($this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'rd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/recurring/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/recurring/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/recurring/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['recurrings'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$recurring_total = $this->model_catalog_recurring->getTotalRecurrings();

		$results = $this->model_catalog_recurring->getRecurrings($filter_data);

		foreach ($results as $result) {
			$data['recurrings'][] = array(
				'recurring_id' => $result['recurring_id'],
				'name'         => $result['name'],
				'sort_order'   => $result['sort_order'],
				'edit'         => $this->url->link('catalog/recurring/edit', 'user_token=' . $this->session->data['user_token'] . '&recurring_id=' . $result['recurring_id'] . $url, true)
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

		$data['sort_name'] = $this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $recurring_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($recurring_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recurring_total - $this->config->get('config_limit_admin'))) ? $recurring_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recurring_total, ceil($recurring_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/recurring_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['recurring_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['recurring_id'])) {
			$data['action'] = $this->url->link('catalog/recurring/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/recurring/edit', 'user_token=' . $this->session->data['user_token'] . '&recurring_id=' . $this->request->get['recurring_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['recurring_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$recurring_info = $this->model_catalog_recurring->getRecurring($this->request->get['recurring_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['recurring_description'])) {
			$data['recurring_description'] = $this->request->post['recurring_description'];
		} elseif (!empty($recurring_info)) {
			$data['recurring_description'] = $this->model_catalog_recurring->getRecurringDescription($recurring_info['recurring_id']);
		} else {
			$data['recurring_description'] = array();
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($recurring_info)) {
			$data['price'] = $recurring_info['price'];
		} else {
			$data['price'] = 0;
		}

		$data['frequencies'] = array();

		$data['frequencies'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day'
		);

		$data['frequencies'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week'
		);

		$data['frequencies'][] = array(
			'text'  => $this->language->get('text_semi_month'),
			'value' => 'semi_month'
		);

		$data['frequencies'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month'
		);

		$data['frequencies'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year'
		);

		if (isset($this->request->post['frequency'])) {
			$data['frequency'] = $this->request->post['frequency'];
		} elseif (!empty($recurring_info)) {
			$data['frequency'] = $recurring_info['frequency'];
		} else {
			$data['frequency'] = '';
		}

		if (isset($this->request->post['duration'])) {
			$data['duration'] = $this->request->post['duration'];
		} elseif (!empty($recurring_info)) {
			$data['duration'] = $recurring_info['duration'];
		} else {
			$data['duration'] = 0;
		}

		if (isset($this->request->post['cycle'])) {
			$data['cycle'] = $this->request->post['cycle'];
		} elseif (!empty($recurring_info)) {
			$data['cycle'] = $recurring_info['cycle'];
		} else {
			$data['cycle'] = 1;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($recurring_info)) {
			$data['status'] = $recurring_info['status'];
		} else {
			$data['status'] = 0;
		}

		if (isset($this->request->post['trial_price'])) {
			$data['trial_price'] = $this->request->post['trial_price'];
		} elseif (!empty($recurring_info)) {
			$data['trial_price'] = $recurring_info['trial_price'];
		} else {
			$data['trial_price'] = 0.00;
		}

		if (isset($this->request->post['trial_frequency'])) {
			$data['trial_frequency'] = $this->request->post['trial_frequency'];
		} elseif (!empty($recurring_info)) {
			$data['trial_frequency'] = $recurring_info['trial_frequency'];
		} else {
			$data['trial_frequency'] = '';
		}

		if (isset($this->request->post['trial_duration'])) {
			$data['trial_duration'] = $this->request->post['trial_duration'];
		} elseif (!empty($recurring_info)) {
			$data['trial_duration'] = $recurring_info['trial_duration'];
		} else {
			$data['trial_duration'] = '0';
		}

		if (isset($this->request->post['trial_cycle'])) {
			$data['trial_cycle'] = $this->request->post['trial_cycle'];
		} elseif (!empty($recurring_info)) {
			$data['trial_cycle'] = $recurring_info['trial_cycle'];
		} else {
			$data['trial_cycle'] = '1';
		}
		if (isset($this->request->post['trial_status'])) {
			$data['trial_status'] = $this->request->post['trial_status'];
		} elseif (!empty($recurring_info)) {
			$data['trial_status'] = $recurring_info['trial_status'];
		} else {
			$data['trial_status'] = 0;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($recurring_info)) {
			$data['sort_order'] = $recurring_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/recurring_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['recurring_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $recurring_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByProfileId($recurring_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
