<?php   
class ControllerCommonDashboard extends Controller {   
	public function index() {
    	$this->language->load('common/dashboard');
	 
		$this->document->setTitle($this->language->get('heading_title'));
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_marketing'] = $this->language->get('text_marketing');
		$this->data['text_online'] = $this->language->get('text_online');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_week'] = $this->language->get('text_week');
		$this->data['text_month'] = $this->language->get('text_month');
		$this->data['text_year'] = $this->language->get('text_year');
		
		// Check install directory exists
 		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_install'] = $this->language->get('error_install');
		} else {
			$this->data['error_install'] = '';
		}
										
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('report/dashboard');
		
		// Total Sales
		$sale_total = $this->model_report_dashboard->getTotalSales();
		
		$this->data['sale_total'] = $this->currency->format($sale_total, $this->config->get('config_currency'));

		$monthly_total = 0;

		$results = $this->model_report_dashboard->getTotalSalesByMonth();
		
		foreach ($results as $result) {
			$monthly_total += $result['total'];
		}
		
		$this->data['sale_growth'] = round(($monthly_total / $sale_total) * 100, 2);
		
		// Total Orders
		$this->load->model('sale/order');
		
		$order_total = $this->model_sale_order->getTotalOrders();
		
		$this->data['order_total'] = $order_total;
				
		$monthly_total = 0;

		$results = $this->model_report_dashboard->getTotalOrdersByMonth();
		
		foreach ($results as $result) {
			$monthly_total += $result['total'];
		}	
					
		$this->data['order_growth'] = round(($monthly_total / $order_total) * 100, 2);		
				
		// Customers
		$this->load->model('sale/customer');
		
		$customer_total = $this->model_sale_customer->getTotalCustomers();
		
		$this->data['customer_total'] = $customer_total;
		
		$monthly_total = 0;

		$results = $this->model_report_dashboard->getTotalOrdersByMonth();
		
		foreach ($results as $result) {
			$monthly_total += $result['total'];
		}
			
		$this->data['customer_growth'] = round(($monthly_total / $customer_total) * 100, 2);		
		
		// Marketing
		$this->load->model('marketing/marketing');
		
		$marketing_total = $this->model_marketing_marketing->getTotalMarketings();

		$this->data['marketing_total'] = $marketing_total;

		$monthly_total = 0;

		$results = $this->model_report_dashboard->getTotalOrdersByMonth();
		
		foreach ($results as $result) {
			$monthly_total += $result['total'];
		}
			
		$this->data['marketing_growth'] = round(($monthly_total / $marketing_total) * 100, 2);




		// Number of people onlne
		$this->load->model('report/online');

		$this->data['total_online'] = $this->model_report_online->getTotalCustomersOnline();
		
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');
		
			$this->model_localisation_currency->updateCurrencies();
		}
		
		$this->template = 'common/dashboard.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function sale() {
		$this->language->load('common/dashboard');
		
		$json = array();
		
		$this->load->model('report/dashboard');
		
		$json['order'] = array();
		$json['customer'] = array();
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
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function marketing() {
		$this->language->load('common/dashboard');
		
		$json = array();
		
		$this->load->model('report/dashboard');
		
		$json['click'] = array();
		$json['order'] = array();
		$json['xaxis'] = array();
		
		$json['click']['label'] = $this->language->get('text_click');
		$json['order']['label'] = $this->language->get('text_order');
		
		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}
		
		switch ($range) {
			default:
			case 'day':
				$results = $this->model_report_dashboard->getTotalMarketingsByDay();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);
					$json['xaxis'][] = array($key, $value['hour']);
				}
				break;
			case 'week':
				$results = $this->model_report_dashboard->getTotalMarketingsByWeek();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);				
					$json['xaxis'][] = array($key, $value['day']);
				}
				break;
			case 'month':
				$results = $this->model_report_dashboard->getTotalMarketingsByMonth();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);						
					$json['xaxis'][] = array($key, $value['day']);
				}
				break;
			case 'year':
				$results = $this->model_report_dashboard->getTotalMarketingsByYear();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);						
					$json['xaxis'][] = array($key, $value['month']);
				}
				break;	
		} 
						
		$this->response->setOutput(json_encode($json));
	}
	
	public function online() {
		$this->language->load('common/dashboard');
		
		$json = array();
		
		$this->load->model('report/dashboard');
		
		$json['online'] = array();
		$json['xaxis'] = array();
		
		$json['online']['label'] = $this->language->get('text_online');
		
		$results = $this->model_report_dashboard->getTotalCustomersOnline();
		
		foreach ($results as $result) {
			$json['online']['data'][] = array($result['time'], $result['total']);
		}	
		
		for ($i = strtotime('-1 hour'); $i < time(); $i = ($i + 300)) {
			$time = (round($i / 300) * 300);
			
			$json['xaxis'][] = array($time, date('H:i', $time));
		}	
						
		$this->response->setOutput(json_encode($json));
	}
}
?>