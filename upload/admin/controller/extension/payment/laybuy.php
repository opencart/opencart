<?php
class ControllerExtensionPaymentLaybuy extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('setting/setting');

		$this->load->model('extension/payment/laybuy');

		$this->load->language('extension/payment/laybuy');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->request->post['laybuy_cron_url'], $this->request->post['laybuy_cron_time']);

			$this->model_setting_setting->editSetting('laybuy', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_reports'] = $this->language->get('tab_reports');

		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_dp_percent'] = $this->language->get('column_dp_percent');
		$data['column_months'] = $this->language->get('column_months');
		$data['column_dp_amount'] = $this->language->get('column_dp_amount');
		$data['column_first_payment'] = $this->language->get('column_first_payment');
		$data['column_last_payment'] = $this->language->get('column_last_payment');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_membership_id'] = $this->language->get('entry_membership_id');
		$data['entry_token'] = $this->language->get('entry_token');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_maximum'] = $this->language->get('entry_maximum');
		$data['entry_max_months'] = $this->language->get('entry_max_months');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_product_ids'] = $this->language->get('entry_product_ids');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_logging'] = $this->language->get('entry_logging');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
		$data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
		$data['entry_order_status_processing'] = $this->language->get('entry_order_status_processing');
		$data['entry_gateway_url'] = $this->language->get('entry_gateway_url');
		$data['entry_api_url'] = $this->language->get('entry_api_url');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_cron_url'] = $this->language->get('entry_cron_url');
		$data['entry_cron_time'] = $this->language->get('entry_cron_time');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_dp_percent'] = $this->language->get('entry_dp_percent');
		$data['entry_months'] = $this->language->get('entry_months');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['help_membership_id'] = $this->language->get('help_membership_id');
		$data['help_token'] = $this->language->get('help_token');
		$data['help_minimum'] = $this->language->get('help_minimum');
		$data['help_maximum'] = $this->language->get('help_maximum');
		$data['help_months'] = $this->language->get('help_months');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_product_ids'] = $this->language->get('help_product_ids');
		$data['help_customer_group'] = $this->language->get('help_customer_group');
		$data['help_logging'] = $this->language->get('help_logging');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_order_status_pending'] = $this->language->get('help_order_status_pending');
		$data['help_order_status_canceled'] = $this->language->get('help_order_status_canceled');
		$data['help_order_status_processing'] = $this->language->get('help_order_status_processing');
		$data['help_cron_url'] = $this->language->get('help_cron_url');
		$data['help_cron_time'] = $this->language->get('help_cron_time');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_fetch'] = $this->language->get('button_fetch');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

		$data['action'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'], true);

		$data['fetch'] = $this->url->link('extension/payment/laybuy/fetch', 'token=' . $this->session->data['token'] . '#reportstab', true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['laybuys_membership_id'])) {
			$data['laybuys_membership_id'] = $this->request->post['laybuys_membership_id'];
		} else {
			$data['laybuys_membership_id'] = $this->config->get('laybuys_membership_id');
		}

		if (isset($this->request->post['laybuy_token'])) {
			$data['laybuy_token'] = $this->request->post['laybuy_token'];
		} elseif ($this->config->has('laybuy_token')) {
			$data['laybuy_token'] = $this->config->get('laybuy_token');
		} else {
			$data['laybuy_token'] = md5(time());
		}

		if (isset($this->request->post['laybuy_min_deposit'])) {
			$data['laybuy_min_deposit'] = $this->request->post['laybuy_min_deposit'];
		} elseif ($this->config->get('laybuy_min_deposit')) {
			$data['laybuy_min_deposit'] = $this->config->get('laybuy_min_deposit');
		} else {
			$data['laybuy_min_deposit'] = '20';
		}

		if (isset($this->request->post['laybuy_max_deposit'])) {
			$data['laybuy_max_deposit'] = $this->request->post['laybuy_max_deposit'];
		} elseif ($this->config->get('laybuy_max_deposit')) {
			$data['laybuy_max_deposit'] = $this->config->get('laybuy_max_deposit');
		} else {
			$data['laybuy_max_deposit'] = '50';
		}

		if (isset($this->request->post['laybuy_max_months'])) {
			$data['laybuy_max_months'] = $this->request->post['laybuy_max_months'];
		} elseif ($this->config->get('laybuy_max_months')) {
			$data['laybuy_max_months'] = $this->config->get('laybuy_max_months');
		} else {
			$data['laybuy_max_months'] = '3';
		}

		if (isset($this->request->post['laybuy_category'])) {
			$data['laybuy_category'] = $this->request->post['laybuy_category'];
		} elseif ($this->config->get('laybuy_category')) {
			$data['laybuy_category'] = $this->config->get('laybuy_category');
		} else {
			$data['laybuy_category'] = array();
		}

		$data['categories'] = array();

		$this->load->model('catalog/category');

		foreach ($data['laybuy_category'] as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['categories'][] = array(
					'category_id' 	=> $category_info['category_id'],
					'name' 			=> ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		if (isset($this->request->post['laybuy_xproducts'])) {
			$data['laybuy_xproducts'] = $this->request->post['laybuy_xproducts'];
		} else {
			$data['laybuy_xproducts'] = $this->config->get('laybuy_xproducts');
		}

		if (isset($this->request->post['laybuy_customer_group'])) {
			$data['laybuy_customer_group'] = $this->request->post['laybuy_customer_group'];
		} elseif ($this->config->get('laybuy_customer_group')) {
			$data['laybuy_customer_group'] = $this->config->get('laybuy_customer_group');
		} else {
			$data['laybuy_customer_group'] = array();
		}

		$data['customer_groups'] = array();

		$this->load->model('customer/customer_group');

		foreach ($data['laybuy_customer_group'] as $customer_group_id) {
			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info) {
				$data['customer_groups'][] = array(
					'customer_group_id' => $customer_group_info['customer_group_id'],
					'name'				=> $customer_group_info['name']
				);
			}
		}

		if (isset($this->request->post['laybuy_logging'])) {
			$data['laybuy_logging'] = $this->request->post['laybuy_logging'];
		} else {
			$data['laybuy_logging'] = $this->config->get('laybuy_logging');
		}

		if (isset($this->request->post['laybuy_total'])) {
			$data['laybuy_total'] = $this->request->post['laybuy_total'];
		} else {
			$data['laybuy_total'] = $this->config->get('laybuy_total');
		}

		if (isset($this->request->post['laybuy_order_status_id_pending'])) {
			$data['laybuy_order_status_id_pending'] = $this->request->post['laybuy_order_status_id_pending'];
		} elseif ($this->config->get('laybuy_order_status_id_pending')) {
			$data['laybuy_order_status_id_pending'] = $this->config->get('laybuy_order_status_id_pending');
		} else {
			$data['laybuy_order_status_id_pending'] = '1';
		}

		if (isset($this->request->post['laybuy_order_status_id_canceled'])) {
			$data['laybuy_order_status_id_canceled'] = $this->request->post['laybuy_order_status_id_canceled'];
		} elseif ($this->config->get('laybuy_order_status_id_canceled')) {
			$data['laybuy_order_status_id_canceled'] = $this->config->get('laybuy_order_status_id_canceled');
		} else {
			$data['laybuy_order_status_id_canceled'] = '7';
		}

		if (isset($this->request->post['laybuy_order_status_id_processing'])) {
			$data['laybuy_order_status_id_processing'] = $this->request->post['laybuy_order_status_id_processing'];
		} elseif ($this->config->get('laybuy_order_status_id_processing')) {
			$data['laybuy_order_status_id_processing'] = $this->config->get('laybuy_order_status_id_processing');
		} else {
			$data['laybuy_order_status_id_processing'] = '2';
		}

		if (isset($this->request->post['laybuy_gateway_url'])) {
			$data['laybuy_gateway_url'] = $this->request->post['laybuy_gateway_url'];
		} elseif ($this->config->get('laybuy_gateway_url')) {
			$data['laybuy_gateway_url'] = $this->config->get('laybuy_gateway_url');
		} else {
			$data['laybuy_gateway_url'] = 'http://lay-buys.com/gateway/';
		}

		if (isset($this->request->post['laybuy_api_url'])) {
			$data['laybuy_api_url'] = $this->request->post['laybuy_api_url'];
		} elseif ($this->config->get('laybuy_api_url')) {
			$data['laybuy_api_url'] = $this->config->get('laybuy_api_url');
		} else {
			$data['laybuy_api_url'] = 'https://lay-buys.com/report/';
		}

		if (isset($this->request->post['laybuy_geo_zone'])) {
			$data['laybuy_geo_zone'] = $this->request->post['laybuy_geo_zone'];
		} else {
			$data['laybuy_geo_zone'] = $this->config->get('laybuy_geo_zone');
		}

		if (isset($this->request->post['laybuy_status'])) {
			$data['laybuy_status'] = $this->request->post['laybuy_status'];
		} else {
			$data['laybuy_status'] = $this->config->get('laybuy_status');
		}

		if (isset($this->request->post['laybuy_sort_order'])) {
			$data['laybuy_sort_order'] = $this->request->post['laybuy_sort_order'];
		} else {
			$data['laybuy_sort_order'] = $this->config->get('laybuy_sort_order');
		}

		$data['laybuy_cron_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/laybuy/cron&token=' . $data['laybuy_token'];

		if ($this->config->get('laybuy_cron_time')) {
			$data['laybuy_cron_time'] = date($this->language->get('datetime_format'), strtotime($this->config->get('laybuy_cron_time')));
		} else {
			$data['laybuy_cron_time'] = $this->language->get('text_no_cron_time');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['laybuys_membership_id'])) {
			$data['error_laybuys_membership_id'] = $this->error['laybuys_membership_id'];
		} else {
			$data['error_laybuys_membership_id'] = '';
		}

		if (isset($this->error['laybuy_token'])) {
			$data['error_laybuy_token'] = $this->error['laybuy_token'];
		} else {
			$data['error_laybuy_token'] = '';
		}

		if (isset($this->error['laybuy_min_deposit'])) {
			$data['error_laybuy_min_deposit'] = $this->error['laybuy_min_deposit'];
		} else {
			$data['error_laybuy_min_deposit'] = '';
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		/* Reports tab */
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_dp_percent'])) {
			$filter_dp_percent = $this->request->get['filter_dp_percent'];
		} else {
			$filter_dp_percent = null;
		}

		if (isset($this->request->get['filter_months'])) {
			$filter_months = $this->request->get['filter_months'];
		} else {
			$filter_months = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'lt.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reports'] = array();

		$filter_data = array(
			'filter_order_id'	=> $filter_order_id,
			'filter_customer'	=> $filter_customer,
			'filter_dp_percent'	=> $filter_dp_percent,
			'filter_months'		=> $filter_months,
			'filter_status'		=> $filter_status,
			'filter_date_added'	=> $filter_date_added,
			'sort'				=> $sort,
			'order'				=> $order,
			'start'				=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'				=> $this->config->get('config_limit_admin')
		);

		$report_total = $this->model_extension_payment_laybuy->getTotalTransactions($filter_data);

		$results = $this->model_extension_payment_laybuy->getTransactions($filter_data);

		foreach ($results as $result) {
			$customer_url = false;

			$customer_id = $this->model_extension_payment_laybuy->getCustomerIdByOrderId($result['order_id']);

			if ($customer_id) {
				$customer_url = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . (int)$customer_id, true);
			}

			$data['reports'][] = array(
				'id'			=> $result['laybuy_transaction_id'],
				'order_id'		=> $result['order_id'],
				'order_url'		=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$result['order_id'], true),
				'customer_name'	=> $result['firstname'] . ' ' . $result['lastname'],
				'customer_url'	=> $customer_url,
				'amount'		=> $this->currency->format($result['amount'], $result['currency']),
				'dp_percent'	=> $result['downpayment'],
				'months'		=> $result['months'],
				'dp_amount'		=> $this->currency->format($result['downpayment_amount'], $result['currency']),
				'first_payment'	=> date($this->language->get('date_format_short'), strtotime($result['first_payment_due'])),
				'last_payment'	=> date($this->language->get('date_format_short'), strtotime($result['last_payment_due'])),
				'status'		=> $this->model_extension_payment_laybuy->getStatusLabel($result['status']),
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'			=> $this->url->link('extension/payment/laybuy/transaction', 'token=' . $this->session->data['token'] . '&id=' . (int)$result['laybuy_transaction_id'], true)
			);
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_dp_percent'])) {
			$url .= '&filter_dp_percent=' . urlencode(html_entity_decode($this->request->get['filter_dp_percent'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_months'])) {
			$url .= '&filter_months=' . $this->request->get['filter_months'];
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

		$data['sort_order_id'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.order_id' . $url . '#reportstab', true);
		$data['sort_customer'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=customer' . $url . '#reportstab', true);
		$data['sort_amount'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.amount' . $url . '#reportstab', true);
		$data['sort_dp_percent'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.downpayment' . $url . '#reportstab', true);
		$data['sort_months'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.months' . $url . '#reportstab', true);
		$data['sort_dp_amount'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.downpayment_amount' . $url . '#reportstab', true);
		$data['sort_first_payment'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.first_payment_due' . $url . '#reportstab', true);
		$data['sort_last_payment'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.last_payment_due' . $url . '#reportstab', true);
		$data['sort_status'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.status' . $url . '#reportstab', true);
		$data['sort_date_added'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '&sort=lt.date_added' . $url . '#reportstab', true);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_dp_percent'])) {
			$url .= '&filter_dp_percent=' . $this->request->get['filter_dp_percent'];
		}

		if (isset($this->request->get['filter_months'])) {
			$url .= '&filter_months=' . $this->request->get['filter_months'];
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

		$pagination = new Pagination();
		$pagination->total = $report_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . $url . '&page={page}#reportstab', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($report_total - $this->config->get('config_limit_admin'))) ? $report_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $report_total, ceil($report_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id']	= $filter_order_id;
		$data['filter_customer']	= $filter_customer;
		$data['filter_dp_percent']	= $filter_dp_percent;
		$data['filter_months']		= $filter_months;
		$data['filter_status']		= $filter_status;
		$data['filter_date_added']	= $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['transaction_statuses'] = $this->model_extension_payment_laybuy->getTransactionStatuses();
		/* End of Reports Tab */

		$data['token'] = $this->session->data['token'];

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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/laybuy', $data));
	}

	public function fetch() {
		$this->load->model('extension/payment/laybuy');

		$this->model_extension_payment_laybuy->log('Fetching transactions');

		if ($this->user->hasPermission('modify', 'extension/payment/laybuy')) {
			$this->load->language('extension/payment/laybuy');

			$json = array();

			$fetched = 0;

			$paypal_profile_id_array = $this->model_extension_payment_laybuy->getPayPalProfileIds();

			if ($paypal_profile_id_array) {
				$paypal_profile_ids = '';

				foreach ($paypal_profile_id_array as $profile_id) {
					$paypal_profile_ids .= $profile_id['paypal_profile_id'] . ',';
				}

				$paypal_profile_ids = rtrim($paypal_profile_ids, ',');

				$data_string = 'mid=' . $this->config->get('laybuys_membership_id') . '&' . 'profileIds=' . $paypal_profile_ids;

				$this->model_extension_payment_laybuy->log('Data String: ' . $data_string);

				$this->model_extension_payment_laybuy->log('API URL: ' . $this->config->get('laybuy_api_url'));

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $this->config->get('laybuy_api_url'));
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					$this->model_extension_payment_laybuy->log('cURL error: ' . curl_errno($ch));
				}
				curl_close($ch);

				$results = json_decode($result, true);

				$this->model_extension_payment_laybuy->log('Response: ' . print_r($results, true));

				if ($results) {
					foreach ($results as $laybuy_ref_id => $reports) {
						$status = $reports['status'];

						$report = $reports['report'];

						$transaction = array();

						$transaction = $this->model_extension_payment_laybuy->getTransactionByLayBuyRefId($laybuy_ref_id);

						$order_id = $transaction['order_id'];

						$paypal_profile_id = $transaction['paypal_profile_id'];

						$months = $transaction['months'];

						$report_content = array();

						$pending_flag = false;

						$next_payment_status = $this->language->get('text_status_1');

						foreach ($report as $month => $payment) {
							$payment['paymentDate'] = date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $payment['paymentDate'])));
							$date = date($this->language->get('date_format_short'), strtotime($payment['paymentDate']));
							$next_payment_date = $payment['paymentDate'];

							if ($payment['type'] == 'd') {
								$report_content[] = array(
									'instalment'	=> 0,
									'amount'		=> $this->currency->format($payment['amount'], $transaction['currency']),
									'date'			=> $date,
									'pp_trans_id'	=> $payment['txnID'],
									'status'		=> $payment['paymentStatus']
								);
							} elseif ($payment['type'] == 'p') {
								$pending_flag = true;

								$report_content[] = array(
									'instalment'	=> $month,
									'amount'		=> $this->currency->format($payment['amount'], $transaction['currency']),
									'date'			=> $date,
									'pp_trans_id'	=> $payment['txnID'],
									'status'		=> $payment['paymentStatus']
								);

								$next_payment_status = $payment['paymentStatus'];
							}
						}

						if ($pending_flag) {
							$start_index = $month + 1;
						} else {
							$start_index = $month + 2;
						}

						if ($month < $months) {
							for ($month = 1; $month <= $months; $month++) {
								$next_payment_date = date("Y-m-d h:i:s", strtotime($next_payment_date . " +1 month"));
								$date = date($this->language->get('date_format_short'), strtotime($next_payment_date));

								$report_content[] = array(
									'instalment'	=> $month,
									'amount'		=> $this->currency->format($transaction['payment_amounts'], $transaction['currency']),
									'date'			=> $date,
									'pp_trans_id'	=> '',
									'status'		=> $next_payment_status
								);
							}
						}

						$report_content = json_encode($report_content);

						switch ($status) {
							case -1: // Cancel
								$this->model_extension_payment_laybuy->log('Transaction #' . $transaction['laybuy_transaction_id'] . ' canceled');
								$this->model_extension_payment_laybuy->updateOrderStatus($order_id, $this->config->get('laybuy_order_status_id_canceled'), $this->language->get('text_comment'));
								$this->model_extension_payment_laybuy->updateTransaction($transaction['laybuy_transaction_id'], '7', $report_content, $start_index);
								$fetched++;
								break;
							case 0: // Pending
								$this->model_extension_payment_laybuy->log('Transaction #' . $transaction['laybuy_transaction_id'] . ' still pending');
								$this->model_extension_payment_laybuy->updateTransaction($transaction['laybuy_transaction_id'], $transaction['status'], $report_content, $start_index);
								$fetched++;
								break;
							case 1: // Paid
								$this->model_extension_payment_laybuy->log('Transaction #' . $transaction['laybuy_transaction_id'] . ' paid');
								$this->model_extension_payment_laybuy->updateOrderStatus($order_id, $this->config->get('laybuy_order_status_id_processing'), $this->language->get('text_comment'));
								$this->model_extension_payment_laybuy->updateTransaction($transaction['laybuy_transaction_id'], '5', $report_content, $start_index);
								$fetched++;
								break;
						}
					}
				}

				if ($fetched) {
					$this->session->data['success'] = sprintf($this->language->get('text_fetched_some'), $fetched);
				} else {
					$this->session->data['success'] = $this->language->get('text_fetched_none');
				}

				$this->response->redirect($this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'], true));
			} else {
				$this->model_extension_payment_laybuy->log('No PayPal Profile IDs to update');

				$this->session->data['success'] = $this->language->get('text_fetched_none');

				$this->response->redirect($this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'], true));
			}
		} else {
			$this->model_extension_payment_laybuy->log('User does not have permission');
		}
	}

	public function install() {
		if ($this->user->hasPermission('modify', 'extension/extension')) {
			$this->load->model('extension/payment/laybuy');

			$this->model_extension_payment_laybuy->install();
		}
	}

	public function uninstall() {
		if ($this->user->hasPermission('modify', 'extension/extension')) {
			$this->load->model('extension/payment/laybuy');

			$this->model_extension_payment_laybuy->uninstall();
		}
	}

	public function transaction($order_page = false) {
		$this->load->model('extension/payment/laybuy');

		$this->load->language('extension/payment/laybuy');

		if (isset($this->request->get['id'])) {
			$id = (int)$this->request->get['id'];
		} else {
			$id = 0;
		}

		$data['id'] = $id;

		if (!$order_page) {
			$this->document->setTitle($this->language->get('heading_transaction_title'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '#reportstab', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_transaction_title'),
			'href' => $this->url->link('extension/payment/laybuy/transaction', 'token=' . $this->session->data['token'] . '&id=' . $id, true)
		);

		$data['heading_title'] = $this->language->get('heading_transaction_title');

		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['cancel'] = $this->url->link('extension/payment/laybuy', 'token=' . $this->session->data['token'] . '#reportstab', true);

		$transaction_info = $this->model_extension_payment_laybuy->getTransaction($id);

		if ($transaction_info) {
			$data['tab_reference'] = $this->language->get('tab_reference');
			$data['tab_customer'] = $this->language->get('tab_customer');
			$data['tab_payment'] = $this->language->get('tab_payment');
			$data['tab_modify'] = $this->language->get('tab_modify');

			$data['text_transaction_details'] = $this->language->get('text_transaction_details');
			$data['text_paypal_profile_id'] = $this->language->get('text_paypal_profile_id');
			$data['text_laybuy_ref_no'] = $this->language->get('text_laybuy_ref_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_firstname'] = $this->language->get('text_firstname');
			$data['text_lastname'] = $this->language->get('text_lastname');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_address'] = $this->language->get('text_address');
			$data['text_suburb'] = $this->language->get('text_suburb');
			$data['text_state'] = $this->language->get('text_state');
			$data['text_country'] = $this->language->get('text_country');
			$data['text_postcode'] = $this->language->get('text_postcode');
			$data['text_status'] = $this->language->get('text_status');
			$data['text_amount'] = $this->language->get('text_amount');
			$data['text_downpayment_percent'] = $this->language->get('text_downpayment_percent');
			$data['text_months'] = $this->language->get('text_months');
			$data['text_downpayment_amount'] = $this->language->get('text_downpayment_amount');
			$data['text_payment_amounts'] = $this->language->get('text_payment_amounts');
			$data['text_first_payment_due'] = $this->language->get('text_first_payment_due');
			$data['text_last_payment_due'] = $this->language->get('text_last_payment_due');
			$data['text_report'] = $this->language->get('text_report');
			$data['text_instalment'] = $this->language->get('text_instalment');
			$data['text_month'] = $this->language->get('text_month');
			$data['text_date'] = $this->language->get('text_date');
			$data['text_pp_trans_id'] = $this->language->get('text_pp_trans_id');
			$data['text_downpayment'] = $this->language->get('text_downpayment');
			$data['text_revise_plan'] = $this->language->get('text_revise_plan');
			$data['text_type_laybuy'] = $this->language->get('text_type_laybuy');
			$data['text_type_buynow'] = $this->language->get('text_type_buynow');
			$data['text_today'] = $this->language->get('text_today');
			$data['text_payment'] = $this->language->get('text_payment');
			$data['text_due_date'] = $this->language->get('text_due_date');
			$data['text_cancel_plan'] = $this->language->get('text_cancel_plan');
			$data['text_remaining'] = $this->language->get('text_remaining');

			$data['button_revise_plan'] = $this->language->get('button_revise_plan');
			$data['button_cancel_plan'] = $this->language->get('button_cancel_plan');

			$data['initial_payments'] = $this->model_extension_payment_laybuy->getInitialPayments();

			$data['months'] = $this->model_extension_payment_laybuy->getMonths();

			$data['currency_symbol_left'] = $this->currency->getSymbolLeft($transaction_info['currency']);

			$data['currency_symbol_right'] = $this->currency->getSymbolRight($transaction_info['currency']);

			$data['store_url'] = HTTPS_CATALOG;

			$data['api_key'] = $this->getApiKey();

			$this->load->model('sale/order');

			$order = $this->model_sale_order->getOrder($transaction_info['order_id']);

			$data['order_info'] = array(
				'currency_value' => $order['currency_value']
			);

			$data['total'] = $this->model_extension_payment_laybuy->getRemainingAmount($transaction_info['amount'], $transaction_info['downpayment_amount'], $transaction_info['payment_amounts'], $transaction_info['transaction']);

			$data['transaction'] = array(
				'paypal_profile_id' => $transaction_info['paypal_profile_id'],
				'laybuy_ref_no' 	=> $transaction_info['laybuy_ref_no'],
				'order_id'        	=> $transaction_info['order_id'],
				'firstname'         => $transaction_info['firstname'],
				'lastname'          => $transaction_info['lastname'],
				'email'	  			=> $transaction_info['email'],
				'address'	  		=> $transaction_info['address'],
				'suburb'			=> $transaction_info['suburb'],
				'state'				=> $transaction_info['state'],
				'country' 			=> $transaction_info['country'],
				'postcode'  		=> $transaction_info['postcode'],
				'status_id'			=> $transaction_info['status'],
				'status'          	=> $this->model_extension_payment_laybuy->getStatusLabel($transaction_info['status']),
				'amount'          	=> $this->currency->format($transaction_info['amount'], $transaction_info['currency']),
				'remaining'        	=> $this->currency->format($this->model_extension_payment_laybuy->getRemainingAmount($transaction_info['amount'], $transaction_info['downpayment_amount'], $transaction_info['payment_amounts'], $transaction_info['transaction']), $transaction_info['currency']),
				'downpayment'	  	=> $transaction_info['downpayment'],
				'months'	  		=> $transaction_info['months'],
				'downpayment_amount'=> $this->currency->format($transaction_info['downpayment_amount'], $transaction_info['currency']),
				'payment_amounts'	=> $this->currency->format($transaction_info['payment_amounts'], $transaction_info['currency']),
				'first_payment_due' => date($this->language->get('date_format_short'), strtotime($transaction_info['first_payment_due'])),
				'last_payment_due'  => date($this->language->get('date_format_short'), strtotime($transaction_info['last_payment_due'])),
				'report'        	=> json_decode($transaction_info['report'], true)
			);
		} else {
			$data['transaction'] = array();

			$data['text_not_found'] = $this->language->get('text_not_found');
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($order_page) {
			return $data;
		}

		$this->response->setOutput($this->load->view('extension/payment/laybuy_transaction', $data));
	}

	public function cancel() {
		$this->load->model('extension/payment/laybuy');

		$this->model_extension_payment_laybuy->log('Canceling transaction');

		if ($this->request->get['source'] == 'order') {
			$this->model_extension_payment_laybuy->log('Called from order page');
		} else {
			$this->model_extension_payment_laybuy->log('Called from extension page');
		}

		if ($this->user->hasPermission('modify', 'extension/payment/laybuy')) {
			$this->load->language('extension/payment/laybuy');

			$json = array();

			$id = (int)$this->request->get['id'];

			$transaction_info = $this->model_extension_payment_laybuy->getTransaction($id);

			$cancel = false;

			if (!$transaction_info['paypal_profile_id']) {
				$this->model_extension_payment_laybuy->log('Transaction has no paypal_profile_id');

				$cancel = true;
			}

			if (!$cancel) {
				$data_string = 'mid=' . $this->config->get('laybuys_membership_id') . '&' . 'paypal_profile_id=' . $transaction_info['paypal_profile_id'];

				$this->model_extension_payment_laybuy->log('Data String: ' . $data_string);

				$ch = curl_init();
				$url = 'https://lay-buys.com/vtmob/deal5cancel.php';
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					$this->model_extension_payment_laybuy->log('cURL error: ' . curl_errno($ch));
				}
				curl_close($ch);

				$this->model_extension_payment_laybuy->log('Response: ' . $result);

				if ($result == 'success') {
					$this->model_extension_payment_laybuy->log('Success');

					$cancel = true;
				} else {
					$this->model_extension_payment_laybuy->log('Failure');
				}
			}

			if ($cancel) {
				$this->model_extension_payment_laybuy->log('Transaction canceled');

				$report_content = json_decode($transaction_info['report'], true);

				foreach ($report_content as &$array) {
					$array['status'] = str_replace('Pending', 'Canceled', $array['status']);
				}

				$report_content = json_encode($report_content);

				$this->model_extension_payment_laybuy->updateTransaction($transaction_info['laybuy_transaction_id'], '7', $report_content, $transaction_info['transaction']);

				$json['success'] = $this->language->get('text_cancel_success');

				$json['order_id'] = $transaction_info['order_id'];

				$json['order_status_id'] = $this->config->get('laybuy_order_status_id_canceled');

				$json['comment'] = sprintf($this->language->get('text_comment_canceled'), $transaction_info['paypal_profile_id']);
			} else {
				$json['error'] = $this->language->get('text_cancel_failure');
			}

			if ($this->request->get['source'] == 'order') {
				$json['reload'] = $this->url->link('sale/order/info', 'order_id=' . (int)$transaction_info['order_id'] . '&token=' . $this->session->data['token'], true);
			} else {
				$json['reload'] = $this->url->link('extension/payment/laybuy/transaction', 'token=' . $this->session->data['token'] . '&id=' . $id, true);
			}

			$this->response->setOutput(json_encode($json));
		} else {
			$this->model_extension_payment_laybuy->log('User does not have permission');
		}
	}

	public function revise() {
		$this->load->model('extension/payment/laybuy');

		$this->model_extension_payment_laybuy->log('Revising transaction');

		if ($this->request->get['source'] == 'order') {
			$this->model_extension_payment_laybuy->log('Called from order page');
		} else {
			$this->model_extension_payment_laybuy->log('Called from extension page');
		}

		if ($this->user->hasPermission('modify', 'extension/payment/laybuy')) {
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->load->language('extension/payment/laybuy');

				$json = array();

				$payment_type = $this->request->post['payment_type'];

				$amount = $this->request->post['amount'];

				$initial = $this->request->post['INIT'];

				$months = $this->request->post['MONTHS'];

				$id = $this->request->get['id'];

				$transaction_info = $this->model_extension_payment_laybuy->getTransaction($id);

				$original = $new = $transaction_info;

				$original['transaction_id'] = $new['transaction_id'] = $transaction_info['laybuy_transaction_id'];

				$original['payment_type'] = $new['payment_type'] = $payment_type;

				$original['type'] = 'Original';

				$new['type'] = 'New';
				$new['status'] = '50';
				$new['amount'] = $amount;
				$new['downpayment'] = $initial;
				$new['months'] = $months;

				$collection = $this->model_extension_payment_laybuy->getRevisedTransactions($id);

				$this->model_extension_payment_laybuy->log('Collection: ' . print_r($collection, true));

				if (count($collection) == 2) {
					$this->model_extension_payment_laybuy->log('Collection == 2');

					foreach ($collection as $request) {
						$this->model_extension_payment_laybuy->log('request: ' . print_r($request, true));

						if ($request['type'] == 'Original') {
							$this->model_extension_payment_laybuy->log('Original: ' . print_r($original, true));

							$this->model_extension_payment_laybuy->updateRevisedTransaction($id, $original);
						} elseif ($request['type'] == 'New') {
							$this->model_extension_payment_laybuy->log('New: ' . print_r($new, true));

							$this->model_extension_payment_laybuy->updateRevisedTransaction($id, $new);

							$revised_transaction = $this->model_extension_payment_laybuy->getRevisedTransaction($id);
						}
					}
				} else {
					$this->model_extension_payment_laybuy->log('Collection != 2');

					$this->model_extension_payment_laybuy->addRevisedTransaction($original);

					$laybuy_revise_request_id = $this->model_extension_payment_laybuy->addRevisedTransaction($new);

					$this->model_extension_payment_laybuy->log('$laybuy_revise_request_id: ' . $laybuy_revise_request_id);

					$revised_transaction = $this->model_extension_payment_laybuy->getRevisedTransaction($laybuy_revise_request_id);
				}

				$this->model_extension_payment_laybuy->log('Revised transaction: ' . print_r($revised_transaction, true));

				if ($revised_transaction['payment_type'] == '1') {
					$pp = '1';
					$pplan = '1';
				} else {
					$pp = '0';
					$pplan = '0';
				}

				$data = array();

				$data['mid']       = $this->config->get('laybuys_membership_id');
				$data['eml']       = $revised_transaction['email'];
				$data['prc']       = $revised_transaction['amount'];
				$data['curr']      = $revised_transaction['currency'];
				$data['pp']        = $pp;
				$data['pplan']     = $pplan;
				$data['init']      = $initial;
				$data['mnth']      = $months;
				$data['convrate']  = '1';
				$data['id']        = $revised_transaction['laybuy_revise_request_id'] . '-' . $revised_transaction['order_id'] . ':' . md5($this->config->get('laybuy_token'));
				$data['RETURNURL'] = HTTPS_CATALOG . 'index.php?route=extension/payment/laybuy/reviseCallback';
				$data['CANCELURL'] = HTTPS_CATALOG . 'index.php?route=extension/payment/laybuy/reviseCancel';

				$data_string = '';

				foreach ($data as $param => $value) {
					$data_string .= $param . '=' . $value . '&';
				}

				$data_string = rtrim($data_string, '&');

				$this->model_extension_payment_laybuy->log('Data String: ' . $data_string);

				$ch = curl_init();
				$url = 'https://lay-buys.com/vtmob/deal5.php';
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					$this->model_extension_payment_laybuy->log('cURL error: ' . curl_errno($ch));
				}
				curl_close($ch);

				if ($result == 'success') {
					$this->model_extension_payment_laybuy->log('Success');

					$this->model_extension_payment_laybuy->updateTransactionStatus($id, '50');

					$json['success'] = $this->language->get('text_revise_success');
				} else {
					$this->model_extension_payment_laybuy->log('Failure');

					$this->model_extension_payment_laybuy->log('Response: ' . print_r($result, true));

					$json['error'] = $this->language->get('text_revise_failure');
				}

				if ($this->request->get['source'] == 'order') {
					$json['reload'] = $this->url->link('sale/order/info', 'order_id=' . (int)$transaction_info['order_id'] . '&token=' . $this->session->data['token'], true);
				} else {
					$json['reload'] = $this->url->link('extension/payment/laybuy/transaction', 'token=' . $this->session->data['token'] . '&id=' . $id, true);
				}

				$this->response->setOutput(json_encode($json));
			} else {
				$this->model_extension_payment_laybuy->log('No $_POST data');
			}
		} else {
			$this->model_extension_payment_laybuy->log('User does not have permission');
		}
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_customer_group'])) {
			$this->load->model('customer/customer_group');

			$results = $this->model_customer_customer_group->getCustomerGroups();

			foreach ($results as $result) {
				$json[] = array(
					'customer_group_id' => $result['customer_group_id'],
					'name'       		=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function order() {
		if ($this->config->get('laybuy_status')) {
			$this->load->model('extension/payment/laybuy');

			$this->load->language('extension/payment/laybuy');

			$order_id = $this->request->get['order_id'];

			$transaction_info = $this->model_extension_payment_laybuy->getTransactionByOrderId($order_id);

			$laybuy_transaction_id = $transaction_info['laybuy_transaction_id'];

			$this->request->get['id'] = $laybuy_transaction_id;

			$data = $this->transaction(true);

			$data['text_payment_info'] = $this->language->get('text_payment_info');

			$data['store_url'] = HTTPS_CATALOG;

			$data['api_key'] = $this->getApiKey();

			return $this->load->view('extension/payment/laybuy_order', $data);
		}
	}

	private function getApiKey() {
		$this->load->model('extension/payment/laybuy');

		$this->model_extension_payment_laybuy->log('Getting API key');

		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$this->model_extension_payment_laybuy->log('API key: ' . $api_info['key']);

			return $api_info['key'];
		} else {
			$this->model_extension_payment_laybuy->log('No API info');

			return;
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/laybuy')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['laybuys_membership_id']) {
			$this->error['laybuys_membership_id'] = $this->language->get('error_membership_id');
		}

		if (!$this->request->post['laybuy_token']) {
			$this->error['laybuy_token'] = $this->language->get('error_token');
		}

		if ($this->request->post['laybuy_min_deposit'] > $this->request->post['laybuy_max_deposit']) {
			$this->error['laybuy_min_deposit'] = $this->language->get('error_min_deposit');
		}

		return !$this->error;
	}
}
