<?php
namespace Opencart\Admin\Controller\Marketing;
class Affiliate extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('marketing/affiliate');

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
			'href' => $this->url->link('marketing/affiliate', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('marketing/affiliate|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('marketing/affiliate|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/affiliate', $data));
	}

	public function list(): void {
		$this->load->language('marketing/affiliate');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_tracking'])) {
			$filter_tracking = $this->request->get['filter_tracking'];
		} else {
			$filter_tracking = '';
		}

		if (isset($this->request->get['filter_commission'])) {
			$filter_commission = $this->request->get['filter_commission'];
		} else {
			$filter_commission = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$this->load->model('customer/customer');

		$data['affiliates'] = [];

		$filter_data = [
			'filter_name'       => $filter_name,
			'filter_tracking'   => $filter_tracking,
			'filter_commission' => $filter_commission,
			'filter_status'     => $filter_status,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'             => $this->config->get('config_pagination_admin')
		];

		$this->load->model('marketing/affiliate');

		$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates($filter_data);

		$results = $this->model_marketing_affiliate->getAffiliates($filter_data);

		foreach ($results as $result) {
			$data['affiliates'][] = [
				'customer_id' => $result['customer_id'],
				'name'        => $result['name'],
				'tracking'    => $result['tracking'],
				'commission'  => $result['commission'],
				'balance'     => $this->currency->format($this->model_customer_customer->getTransactionTotal($result['customer_id']), $this->config->get('config_currency')),
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'customer'    => $this->url->link('customer/customer|form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']),
				'edit'        => $this->url->link('marketing/affiliate|form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_tracking'] = $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.tracking' . $url);
		$data['sort_commission'] = $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.commission' . $url);
		$data['sort_status'] = $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.status' . $url);
		$data['sort_date_added'] = $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.date_added' . $url);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $affiliate_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketing/affiliate|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($affiliate_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($affiliate_total - $this->config->get('config_pagination_admin'))) ? $affiliate_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $affiliate_total, ceil($affiliate_total / $this->config->get('config_pagination_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_tracking'] = $filter_tracking;
		$data['filter_commission'] = $filter_commission;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('marketing/affiliate_list', $data);
	}

	public function form(): void {
		$this->load->language('marketing/affiliate');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = $this->config->get('config_file_max_size');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

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
			'href' => $this->url->link('marketing/affiliate', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('marketing/affiliate|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('marketing/affiliate', 'user_token=' . $this->session->data['user_token'] . $url);

		// Affiliate
		if (isset($this->request->get['customer_id'])) {
			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($this->request->get['customer_id']);
		}

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = (int)$this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = (int)$this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (!empty($affiliate_info)) {
			$data['customer'] = $affiliate_info['customer'];
		} else {
			$data['customer'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['customer_group_id'] = $affiliate_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['company'] = $affiliate_info['company'];
		} else {
			$data['company'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['website'] = $affiliate_info['website'];
		} else {
			$data['website'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['tracking'] = $affiliate_info['tracking'];
		} else {
			$data['tracking'] = token(10);
		}

		if (!empty($affiliate_info)) {
			$data['commission'] = $affiliate_info['commission'];
		} else {
			$data['commission'] = $this->config->get('config_affiliate_commission');
		}

		if (!empty($affiliate_info)) {
			$data['status'] = $affiliate_info['status'];
		} else {
			$data['status'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['tax'] = $affiliate_info['tax'];
		} else {
			$data['tax'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['payment'] = $affiliate_info['payment'];
		} else {
			$data['payment'] = 'cheque';
		}

		if (!empty($affiliate_info)) {
			$data['cheque'] = $affiliate_info['cheque'];
		} else {
			$data['cheque'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['paypal'] = $affiliate_info['paypal'];
		} else {
			$data['paypal'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_name'] = $affiliate_info['bank_name'];
		} else {
			$data['bank_name'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
		} else {
			$data['bank_branch_number'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
		} else {
			$data['bank_swift_code'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_account_name'] = $affiliate_info['bank_account_name'];
		} else {
			$data['bank_account_name'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_account_number'] = $affiliate_info['bank_account_number'];
		} else {
			$data['bank_account_number'] = '';
		}

		$data['custom_fields'] = [];

		$filter_data = [
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		];

		// Custom Fields
		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = [
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			];
		}

		if (!empty($affiliate_info)) {
			$data['affiliate_custom_field'] = json_decode($affiliate_info['custom_field'], true);
		} else {
			$data['affiliate_custom_field'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/affiliate_form', $data));
	}

	public function save(): void {
		$this->load->language('marketing/affiliate');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketing/affiliate')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($this->request->post['customer_id']);

		if (!$customer_info) {
			$json['error']['customer'] = $this->language->get('error_customer');
		}

		// Check to see if customer is already a affiliate
		$this->load->model('marketing/affiliate');

		$affiliate_info = $this->model_marketing_affiliate->getAffiliate($this->request->post['customer_id']);

		if ($affiliate_info && (!isset($this->request->post['customer_id']) || ($this->request->post['customer_id'] != $affiliate_info['customer_id']))) {
			$json['error']['warning'] = $this->language->get('error_already');
		}

		if (!$this->request->post['tracking']) {
			$json['error']['tracking'] = $this->language->get('error_tracking');
		}

		$affiliate_info = $this->model_marketing_affiliate->getAffiliateByTracking($this->request->post['tracking']);

		if ($affiliate_info && (!isset($this->request->post['customer_id']) || ($this->request->post['customer_id'] != $affiliate_info['customer_id']))) {
			$json['error']['tracking'] = $this->language->get('error_exists');
		}

		// Payment validation
		if ($this->request->post['payment'] == 'cheque' && $this->request->post['cheque'] == '') {
			$json['error']['cheque'] = $this->language->get('error_cheque');
		} elseif ($this->request->post['payment'] == 'paypal' && ((utf8_strlen($this->request->post['paypal']) > 96) || !filter_var($this->request->post['paypal'], FILTER_VALIDATE_EMAIL))) {
			$json['error']['paypal'] = $this->language->get('error_paypal');
		} elseif ($this->request->post['payment'] == 'bank') {
			if ($this->request->post['bank_account_name'] == '') {
				$json['error']['bank_account_name'] = $this->language->get('error_bank_account_name');
			}

			if ($this->request->post['bank_account_number'] == '') {
				$json['error']['bank_account_number'] = $this->language->get('error_bank_account_number');
			}
		}

		// Custom field validation
		if ($customer_info) {
			$this->load->model('customer/custom_field');

			$custom_fields = $this->model_customer_custom_field->getCustomFields(['filter_customer_group_id' => $customer_info['customer_group_id']]);

			foreach ($custom_fields as $custom_field) {
				if (($custom_field['location'] == 'affiliate') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['location'] == 'affiliate') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			if (!$this->request->post['customer_id']) {
				$json['customer_id'] = $this->model_marketing_affiliate->addAffiliate($this->request->post);
			} else {
				$this->model_marketing_affiliate->editAffiliate($this->request->post['customer_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('marketing/affiliate');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketing/affiliate')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('marketing/affiliate');

			foreach ($selected as $affiliate_id) {
				$this->model_marketing_affiliate->deleteAffiliate($affiliate_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function report(): void {
		$this->load->language('marketing/affiliate');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reports'] = [];

		$this->load->model('marketing/affiliate');
		$this->load->model('customer/customer');
		$this->load->model('setting/store');

		$results = $this->model_marketing_affiliate->getReports($customer_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$store_info = $this->model_setting_store->getStore($result['store_id']);

			if ($store_info) {
				$store = $store_info['name'];
			} elseif (!$result['store_id']) {
				$store = $this->config->get('config_name');
			} else {
				$store = '';
			}

			$data['reports'][] = [
				'ip'         => $result['ip'],
				'account'    => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'store'      => $store,
				'country'    => $result['country'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&filter_ip=' . $result['ip'])
			];
		}

		$report_total = $this->model_marketing_affiliate->getTotalReports($customer_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $report_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('marketing/affiliate|report', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($report_total - 10)) ? $report_total : ((($page - 1) * 10) + 10), $report_total, ceil($report_total / 10));

		$this->response->setOutput($this->load->view('marketing/affiliate_report', $data));
	}
}
