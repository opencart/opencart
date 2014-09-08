<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_order_total'] = $this->language->get('text_order_total');
		$data['text_sale_total'] = $this->language->get('text_sale_total');
		$data['text_customer_total'] = $this->language->get('text_customer_total');
		$data['text_online_total'] = $this->language->get('text_online_total');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');
		$data['text_day'] = $this->language->get('text_day');
		$data['text_week'] = $this->language->get('text_week');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_view'] = $this->language->get('text_view');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['token'] = $this->session->data['token'];

		// Total Orders
		$this->load->model('sale/order');

		$data['order_total'] = $this->model_sale_order->getTotalOrders();
		$data['order'] = $this->url->link('sales/order', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('report/dashboard');
		
		// Total Sales
		$data['sale_total'] = $this->currency->format($this->model_report_dashboard->getTotalSales(), $this->config->get('config_currency'));
		$data['sale'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

		// Customers
		$this->load->model('sale/customer');

		$data['customer_total'] = $this->model_sale_customer->getTotalCustomers();
		$data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');

		// Customers Online
		$data['online_total'] = $this->model_report_dashboard->getTotalCustomersOnline();
		$data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');

		$data['activities'] = array();

		$results = $this->model_report_dashboard->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_' . $result['key']), unserialize($result['data']));

			$find = array(
				'customer_id=',
				'order_id=',
				'affiliate_id='
			);

			$replace = array(
				$this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=', 'SSL'),
				$this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=', 'SSL'),
				$this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=', 'SSL')
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		// Last 5 Orders
		$data['orders'] = array();

		$filter_data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);

		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'view'       => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
	}
	
	public function map() {
		$this->load->language('common/dashboard');
		
		$json = array();
		
		$this->load->model('report/dashboard');
		
		$results = $this->model_report_dashboard->getTotalOrdersByCountry();
		
		foreach ($results as $result) {
			$json[strtolower($result['iso_code_2'])] = array(
				'total'  => $result['total'],
				'amount' => $this->currency->format($result['amount'], $this->config->get('currency_code'))
			);
		}
	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function sale() {
		$this->load->language('common/dashboard');

		$json = array();

		$this->load->model('report/dashboard');

		$json['orders'] = array();
		$json['customers'] = array();
		$json['xaxis'] = array();

		$json['order']['label'] = $this->language->get('text_order');
		$json['customer']['label'] = $this->language->get('text_customer');

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_report_dashboard->getTotalOrdersByDay();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_dashboard->getTotalCustomersByDay();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}

				for ($i = 0; $i < 24; $i++) {
					$json['xaxis'][] = array($i, $i);
				}
				break;
			case 'week':
				$results = $this->model_report_dashboard->getTotalOrdersByWeek();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_dashboard->getTotalCustomersByWeek();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}

				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$json['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
				}
				break;
			case 'month':
				$results = $this->model_report_dashboard->getTotalOrdersByMonth();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_dashboard->getTotalCustomersByMonth();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$json['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
				}
				break;
			case 'year':
				$results = $this->model_report_dashboard->getTotalOrdersByYear();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_dashboard->getTotalCustomersByYear();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}

				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
