<?php   
class ControllerCommonDashboard extends Controller {   
	public function index() {
    	$this->language->load('common/dashboard');
	 
		$this->document->setTitle($this->language->get('heading_title'));
		
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->user->getUsername());
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_marketing'] = $this->language->get('text_marketing');
		$data['text_day'] = $this->language->get('text_day');
		$data['text_week'] = $this->language->get('text_week');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		
		$data['column_comment'] = $this->language->get('column_comment');
		$data['column_date_added'] = $this->language->get('column_date_added');
		
		$data['button_refresh'] = $this->language->get('button_refresh');
		
		$data['tab_sale'] = $this->language->get('tab_sale');
		$data['tab_marketing'] = $this->language->get('tab_marketing');
		$data['tab_online'] = $this->language->get('tab_online');
		$data['tab_activity'] = $this->language->get('tab_activity');
		
		// Check install directory exists
 		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}
										
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

		$data['token'] = $this->session->data['token'];
		
		$this->load->model('report/dashboard');
		
		// Total Sales
		$sale_total = $this->model_report_dashboard->getTotalSales();
		
		$data['sale_total'] = $this->currency->format($sale_total, $this->config->get('config_currency'));
		
		// Total Orders
		$this->load->model('sale/order');
		
		$data['order_total'] = $this->model_sale_order->getTotalOrders();
				
		// Customers
		$this->load->model('sale/customer');
		
		$customer_total = $this->model_sale_customer->getTotalCustomers();
		
		$data['customer_total'] = $customer_total;
		
		// Marketing
		$this->load->model('marketing/marketing');
		
		$data['marketing_total'] = $this->model_marketing_marketing->getTotalMarketings();
		
		$data['activities'] = array();
				
		$results = $this->model_report_dashboard->getActivities();
    	
		foreach ($results as $result) {
      		$data['activities'][] = array(
				'comment'    => $result['comment'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}	
				
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
  	}
	
	public function sale() {
		$this->language->load('common/dashboard');
		
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
				}
				
				for ($i = 0; $i < 24; $i++) {
					$json['xaxis'][] = array($i, $i);
				}		
				break;
			case 'week':
				$results = $this->model_report_dashboard->getTotalMarketingsByWeek();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);				
				}
				
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));
					
					$json['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
				}				
				break;
			case 'month':
				$results = $this->model_report_dashboard->getTotalMarketingsByMonth();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);						
				}
				
				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;
					
					$json['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
				}	
				break;
			case 'year':
				$results = $this->model_report_dashboard->getTotalMarketingsByYear();
				
				foreach ($results as $key => $value) {
					$json['click']['data'][] = array($key, $value['click']);
					$json['order']['data'][] = array($key, $value['order']);						
				}
				
				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
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