<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Tax Rate
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class TaxRate extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/tax_rate');

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
			'href' => $this->url->link('localisation/tax_rate', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/tax_rate.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/tax_rate.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/tax_rate', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/tax_rate');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'tr.name';
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

		$data['action'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['tax_rates'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/tax_rate');

		$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRates();

		$results = $this->model_localisation_tax_rate->getTaxRates($filter_data);

		foreach ($results as $result) {
			$data['tax_rates'][] = [
				'tax_rate_id'   => $result['tax_rate_id'],
				'name'          => $result['name'],
				'rate'          => $result['rate'],
				'type'          => ($result['type'] == 'F' ? $this->language->get('text_amount') : $this->language->get('text_percent')),
				'geo_zone'      => $result['geo_zone'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'edit'          => $this->url->link('localisation/tax_rate.form', 'user_token=' . $this->session->data['user_token'] . '&tax_rate_id=' . $result['tax_rate_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=tr.name' . $url);
		$data['sort_rate'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=tr.rate' . $url);
		$data['sort_type'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=tr.type' . $url);
		$data['sort_geo_zone'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=gz.name' . $url);
		$data['sort_date_added'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=tr.date_added' . $url);
		$data['sort_date_modified'] = $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=tr.date_modified' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $tax_rate_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/tax_rate.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($tax_rate_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($tax_rate_total - $this->config->get('config_pagination_admin'))) ? $tax_rate_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $tax_rate_total, ceil($tax_rate_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/tax_rate_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/tax_rate');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['tax_rate_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/tax_rate', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/tax_rate.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/tax_rate', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['tax_rate_id'])) {
			$this->load->model('localisation/tax_rate');

			$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($this->request->get['tax_rate_id']);
		}

		if (isset($this->request->get['tax_rate_id'])) {
			$data['tax_rate_id'] = (int)$this->request->get['tax_rate_id'];
		} else {
			$data['tax_rate_id'] = 0;
		}

	    if (!empty($tax_rate_info)) {
			$data['name'] = $tax_rate_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($tax_rate_info)) {
			$data['rate'] = $tax_rate_info['rate'];
		} else {
			$data['rate'] = '';
		}

		if (!empty($tax_rate_info)) {
			$data['type'] = $tax_rate_info['type'];
		} else {
			$data['type'] = '';
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->get['tax_rate_id'])) {
			$data['tax_rate_customer_group'] = $this->model_localisation_tax_rate->getCustomerGroups($this->request->get['tax_rate_id']);
		} else {
			$data['tax_rate_customer_group'] = [$this->config->get('config_customer_group_id')];
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (!empty($tax_rate_info)) {
			$data['geo_zone_id'] = $tax_rate_info['geo_zone_id'];
		} else {
			$data['geo_zone_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/tax_rate_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/tax_rate');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/tax_rate')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['name']) < 3) || (oc_strlen($this->request->post['name']) > 32)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['rate']) {
			$json['error']['rate'] = $this->language->get('error_rate');
		}

		if (!$json) {
			$this->load->model('localisation/tax_rate');

			if (!$this->request->post['tax_rate_id']) {
				$json['tax_rate_id'] = $this->model_localisation_tax_rate->addTaxRate($this->request->post);
			} else {
				$this->model_localisation_tax_rate->editTaxRate($this->request->post['tax_rate_id'], $this->request->post);
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
		$this->load->language('localisation/tax_rate');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/tax_rate')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/tax_class');

		foreach ($this->request->post['selected'] as $tax_rate_id) {
			$tax_rule_total = $this->model_localisation_tax_class->getTotalTaxRulesByTaxRateId($tax_rate_id);

			if ($tax_rule_total) {
				$json['error'] = sprintf($this->language->get('error_tax_rule'), $tax_rule_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/tax_rate');

			foreach ($selected as $tax_rate_id) {
				$this->model_localisation_tax_rate->deleteTaxRate($tax_rate_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
