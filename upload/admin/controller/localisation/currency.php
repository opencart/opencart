<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/currency');

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
			'href' => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['refresh'] = $this->url->link('localisation/currency.refresh', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['add'] = $this->url->link('localisation/currency.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/currency.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('localisation/currency.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('localisation/currency.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/currency', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/currency');

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['action'] = $this->url->link('localisation/currency.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Currencies
		$data['currencies'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/currency');

		$results = $this->model_localisation_currency->getCurrencies($filter_data);

		foreach ($results as $result) {
			$data['currencies'][] = ['edit' => $this->url->link('localisation/currency.form', 'user_token=' . $this->session->data['user_token'] . '&currency_id=' . $result['currency_id'] . $url)] + $result;
		}

		// Default
		$data['code'] = $this->config->get('config_currency');

		// Total Currencies
		$currency_total = $this->model_localisation_currency->getTotalCurrencies();

		// Pagination
		$data['total'] = $currency_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('localisation/currency.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($currency_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($currency_total - $this->config->get('config_pagination_admin'))) ? $currency_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $currency_total, ceil($currency_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('localisation/currency_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['currency_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/currency.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url);

		// Currency
		if (isset($this->request->get['currency_id'])) {
			$this->load->model('localisation/currency');

			$currency_info = $this->model_localisation_currency->getCurrency((int)$this->request->get['currency_id']);
		}

		if (!empty($currency_info)) {
			$data['currency_id'] = $currency_info['currency_id'];
		} else {
			$data['currency_id'] = 0;
		}

		if (!empty($currency_info)) {
			$data['title'] = $currency_info['title'];
		} else {
			$data['title'] = '';
		}

		if (!empty($currency_info)) {
			$data['code'] = $currency_info['code'];
		} else {
			$data['code'] = '';
		}

		if (!empty($currency_info)) {
			$data['symbol_left'] = $currency_info['symbol_left'];
		} else {
			$data['symbol_left'] = '';
		}

		if (!empty($currency_info)) {
			$data['symbol_right'] = $currency_info['symbol_right'];
		} else {
			$data['symbol_right'] = '';
		}

		if (!empty($currency_info)) {
			$data['decimal_place'] = $currency_info['decimal_place'];
		} else {
			$data['decimal_place'] = '';
		}

		if (!empty($currency_info)) {
			$data['value'] = $currency_info['value'];
		} else {
			$data['value'] = '';
		}

		if (!empty($currency_info)) {
			$data['status'] = $currency_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/currency_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'currency_id'   => 0,
			'title'         => '',
			'code'          => '',
			'symbol_left'   => '',
			'symbol_right'  => '',
			'decimal_place' => 0,
			'value'         => 0.0,
			'status'        => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['title'], 3, 32)) {
			$json['error']['title'] = $this->language->get('error_title');
		}

		if (oc_strlen($post_info['code']) != 3) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		// Currency
		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($post_info['code']);

		if ($currency_info && (!$post_info['currency_id'] || ($currency_info['currency_id'] != $post_info['currency_id']))) {
			$json['error']['code'] = $this->language->get('error_exists');
		}

		if (!$json) {
			if (!$post_info['currency_id']) {
				$json['currency_id'] = $this->model_localisation_currency->addCurrency($post_info);
			} else {
				$this->model_localisation_currency->editCurrency($post_info['currency_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('localisation/currency');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/currency');

			foreach ($selected as $currency_id) {
				$this->model_localisation_currency->editStatus((int)$currency_id, true);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Disable
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->load->language('localisation/currency');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/currency');

			foreach ($selected as $currency_id) {
				$this->model_localisation_currency->editStatus((int)$currency_id, false);
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
		$this->load->language('localisation/currency');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Currency
		$this->load->model('localisation/currency');

		// Setting
		$this->load->model('setting/store');

		// Orders
		$this->load->model('sale/order');

		foreach ($selected as $currency_id) {
			$currency_info = $this->model_localisation_currency->getCurrency($currency_id);

			if ($currency_info) {
				if ($this->config->get('config_currency') == $currency_info['code']) {
					$json['error'] = $this->language->get('error_default');
				}

				$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);

				if ($store_total) {
					$json['error'] = sprintf($this->language->get('error_store'), $store_total);
				}
			}

			// Total Orders
			$order_total = $this->model_sale_order->getTotalOrdersByCurrencyId($currency_id);

			if ($order_total) {
				$json['error'] = sprintf($this->language->get('error_order'), $order_total);
			}
		}

		if (!$json) {
			foreach ($selected as $currency_id) {
				$this->model_localisation_currency->deleteCurrency($currency_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Refresh
	 *
	 * @return void
	 */
	public function refresh(): void {
		$this->load->language('localisation/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/admin/currency.refresh',
				'args'   => []
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
