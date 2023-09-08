<?php
namespace Opencart\Admin\Controller\Marketing;
/**
 * Class Affiliate
 *
 * @package Opencart\Admin\Controller\Marketing
 */
class Affiliate extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketing/affiliate');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_tracking'])) {
			$filter_tracking = $this->request->get['filter_tracking'];
		} else {
			$filter_tracking = '';
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$filter_payment_method = (string)$this->request->get['filter_payment_method'];
		} else {
			$filter_payment_method = '';
		}

		if (isset($this->request->get['filter_commission'])) {
			$filter_commission = $this->request->get['filter_commission'];
		} else {
			$filter_commission = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_pagination');
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
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

		$data['csv'] = $this->url->link('marketing/affiliate.csv', 'user_token=' . $this->session->data['user_token']);
		$data['complete'] = $this->url->link('marketing/affiliate.complete', 'user_token=' . $this->session->data['user_token']);
		$data['add'] = $this->url->link('marketing/affiliate.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('marketing/affiliate.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['payment_methods'] = [];

		$data['payment_methods'][] = [
			'text'  => $this->language->get('text_cheque'),
			'value' => 'cheque'
		];

		$data['payment_methods'][] = [
			'text'  => $this->language->get('text_paypal'),
			'value' => 'paypal'
		];

		$data['payment_methods'][] = [
			'text'  => $this->language->get('text_bank'),
			'value' => 'bank'
		];

		$data['limits'] = [];

		$data['limits'][] = [
			'text'  => $this->config->get('config_pagination'),
			'value' => $this->config->get('config_pagination')
		];

		$data['limits'][] = [
			'text'  => 100,
			'value' => 100
		];

		$data['limits'][] = [
			'text'  => 200,
			'value' => 200
		];

		$data['limits'][] = [
			'text'  => 400,
			'value' => 400
		];

		$data['limits'][] = [
			'text'  => 800,
			'value' => 800
		];

		$data['user_token'] = $this->session->data['user_token'];

		$data['filter_customer'] = $filter_customer;
		$data['filter_tracking'] = $filter_tracking;
		$data['filter_payment_method'] = $filter_payment_method;
		$data['filter_commission'] = $filter_commission;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;
		$data['filter_status'] = $filter_status;

		$data['limit'] = $limit;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/affiliate', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketing/affiliate');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = (string)$this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_tracking'])) {
			$filter_tracking = (string)$this->request->get['filter_tracking'];
		} else {
			$filter_tracking = '';
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$filter_payment_method = (string)$this->request->get['filter_payment_method'];
		} else {
			$filter_payment_method = '';
		}

		if (isset($this->request->get['filter_commission'])) {
			$filter_commission = $this->request->get['filter_commission'];
		} else {
			$filter_commission = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = (string)$this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = (string)$this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_pagination');
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['action'] = $this->url->link('marketing/affiliate.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['affiliates'] = [];

		$filter_data = [
			'filter_name'           => $filter_customer,
			'filter_tracking'       => $filter_tracking,
			'filter_payment_method' => $filter_payment_method,
			'filter_commission'     => $filter_commission,
			'filter_date_from'      => $filter_date_from,
			'filter_date_to'        => $filter_date_to,
			'filter_status'         => $filter_status,
			'sort'                  => $sort,
			'order'                 => $order,
			'start'                 => ($page - 1) * $limit,
			'limit'                 => $limit
		];

		$this->load->model('marketing/affiliate');
		$this->load->model('customer/customer');

		$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates($filter_data);

		$results = $this->model_marketing_affiliate->getAffiliates($filter_data);

		foreach ($results as $result) {
			$data['affiliates'][] = [
				'customer_id' => $result['customer_id'],
				'name'        => $result['name'],
				'tracking'    => $result['tracking'],
				'commission'  => $result['commission'],
				'balance'     => $this->currency->format($result['balance'], $this->config->get('config_currency')),
				'status'      => $result['status'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'customer'    => $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']),
				'edit'        => $this->url->link('marketing/affiliate.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sort_name'] = $this->url->link('marketing/affiliate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_tracking'] = $this->url->link('marketing/affiliate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.tracking' . $url);
		$data['sort_commission'] = $this->url->link('marketing/affiliate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.commission' . $url);
		$data['sort_date_added'] = $this->url->link('marketing/affiliate.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ca.date_added' . $url);

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $affiliate_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketing/affiliate.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($affiliate_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($affiliate_total - $limit)) ? $affiliate_total : ((($page - 1) * $limit) + $limit), $affiliate_total, ceil($affiliate_total / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		return $this->load->view('marketing/affiliate_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('marketing/affiliate');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_tracking'])) {
			$url .= '&filter_tracking=' . $this->request->get['filter_tracking'];
		}

		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}

		if (isset($this->request->get['filter_commission'])) {
			$url .= '&filter_commission=' . $this->request->get['filter_commission'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
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

		$data['save'] = $this->url->link('marketing/affiliate.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketing/affiliate', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['upload'] = $this->url->link('tool/upload.upload', 'user_token=' . $this->session->data['user_token']);

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
			$data['tracking'] = oc_token(10);
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
			$data['payment_method'] = $affiliate_info['payment_method'];
		} else {
			$data['payment_method'] = 'cheque';
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
			if ($custom_field['status']) {
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
		}

		if (!empty($affiliate_info)) {
			$data['affiliate_custom_field'] = json_decode($affiliate_info['custom_field'], true);
		} else {
			$data['affiliate_custom_field'] = [];
		}

		$data['history'] = $this->load->controller('customer/customer.getHistory');
		$data['transaction'] = $this->load->controller('customer/customer.getTransaction');
		$data['report'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/affiliate_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('marketing/affiliate');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketing/affiliate')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer((int)$this->request->post['customer_id']);

		if (!$customer_info) {
			$json['error']['customer'] = $this->language->get('error_customer');
		}

		// Check to see if customer is already a affiliate
		$this->load->model('marketing/affiliate');

		$affiliate_info = $this->model_marketing_affiliate->getAffiliate((int)$this->request->post['customer_id']);

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
		if (empty($this->request->post['payment_method'])) {
			$json['error']['payment_method'] = $this->language->get('error_payment_method');
		}

		if ($this->request->post['payment_method'] == 'cheque' && $this->request->post['cheque'] == '') {
			$json['error']['cheque'] = $this->language->get('error_cheque');
		} elseif ($this->request->post['payment_method'] == 'paypal' && ((oc_strlen($this->request->post['paypal']) > 96) || !filter_var($this->request->post['paypal'], FILTER_VALIDATE_EMAIL))) {
			$json['error']['paypal'] = $this->language->get('error_paypal');
		} elseif ($this->request->post['payment_method'] == 'bank') {
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
				if ($custom_field['status']) {
					if (($custom_field['location'] == 'affiliate') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['location'] == 'affiliate') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Use affiliate
			if (!$affiliate_info) {
				$this->model_marketing_affiliate->addAffiliate($this->request->post);
			} else {
				$this->model_marketing_affiliate->editAffiliate($this->request->post['customer_id'], $this->request->post);
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

	/**
	 * @return void
	 */
	public function calculate() {
		$this->load->language('marketing/affiliate');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketing/affiliate')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('marketing/affiliate');
			$this->load->model('customer/customer');

			$results = $this->model_marketing_affiliate->getAffiliates(['filter_status' => 1]);

			foreach ($results as $result) {
				$this->model_marketing_affiliate->editBalance($result['customer_id'], $this->model_customer_customer->getTransactionTotal($result['customer_id']));
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return \Opencart\System\Engine\Action|void
	 */
	public function csv() {
		$this->load->language('marketing/affiliate');

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if ($this->user->hasPermission('modify', 'marketing/affiliate')) {
			$this->load->model('marketing/affiliate');

			$csv = '';

			foreach ($selected as $customer_id) {
				$affiliate_info = $this->model_marketing_affiliate->getAffiliate($customer_id);

				if ($affiliate_info && $affiliate_info['status'] && (float)$affiliate_info['balance'] > 0) {
					$balance = $this->currency->format($affiliate_info['balance'], $this->config->get('config_currency'), 1.00000000, false);

					if ($affiliate_info['payment_method'] == 'cheque') {
						$csv .= $affiliate_info['cheque'] . ',' . $balance . ',' . $this->config->get('config_currency') . ',' . $affiliate_info['customer'] . "\n";
					}

					if ($affiliate_info['payment_method'] == 'paypal') {
						$csv .= $affiliate_info['paypal'] . ',' . $balance . ',' . $this->config->get('config_currency') . ',' . $affiliate_info['customer'] . ',Thanks for your business!' . "\n";
					}

					if ($affiliate_info['payment_method'] == 'bank') {
						$csv .= $affiliate_info['bank_name'] . ',' . $affiliate_info['bank_branch_number'] . ',' . $affiliate_info['bank_swift_code'] . ',' . $affiliate_info['bank_account_name'] . ',' . $affiliate_info['bank_account_number'] . ',' . $balance . ',' . $this->config->get('config_currency') . ',' . $affiliate_info['customer'] . "\n";
					}
				}
			}

			if (!headers_sent()) {
				header('Pragma: public');
				header('Expires: 0');
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Transfer-Encoding: binary');
				header('Content-Disposition: attachment; filename=payout-' . date('d-m-Y') . '.csv"');
				header('Content-Length: ' . strlen($csv));

				print($csv);
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			return new \Opencart\System\Engine\Action('error/permission');
		}
	}

	/**
	 * @return void
	 */
	public function complete() {
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
			$this->load->model('customer/customer');

			foreach ($selected as $customer_id) {
				$affiliate_info = $this->model_marketing_affiliate->getAffiliate($customer_id);

				if ($affiliate_info && $affiliate_info['status'] && (float)$affiliate_info['balance'] > 0) {
					$this->model_customer_customer->addTransaction($affiliate_info['customer_id'], $this->language->get('text_payment_' . $affiliate_info['payment_method']), -$affiliate_info['balance']);

					$this->model_marketing_affiliate->editBalance($affiliate_info['customer_id'], 0);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function report(): void {
		$this->load->language('marketing/affiliate');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * @return string
	 */
	private function getReport(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'marketing/affiliate.report') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['reports'] = [];

		$this->load->model('marketing/affiliate');
		$this->load->model('customer/customer');
		$this->load->model('setting/store');

		$results = $this->model_marketing_affiliate->getReports($customer_id, ($page - 1) * $limit, $limit);

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
			'limit' => $limit,
			'url'   => $this->url->link('marketing/affiliate.report', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($report_total - $limit)) ? $report_total : ((($page - 1) * $limit) + $limit), $report_total, ceil($report_total / $limit));

		return $this->load->view('marketing/affiliate_report', $data);
	}

	/**
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		$filter_data = [
			'filter_name'  => $filter_name,
			'filter_email' => $filter_email,
			'start'        => 0,
			'limit'        => 5
		];

		$this->load->model('marketing/affiliate');

		$results = $this->model_marketing_affiliate->getAffiliates($filter_data);

		foreach ($results as $result) {
			$json[] = [
				'customer_id' => $result['customer_id'],
				'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				'email'       => $result['email']
			];
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
