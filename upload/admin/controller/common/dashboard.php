<?php   
class ControllerCommonDashboard extends Controller {   
	public function index() {
    	$this->language->load('common/dashboard');
	 
		$this->document->setTitle($this->language->get('heading_title'));
		
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_marketing'] = $this->language->get('text_marketing');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_day'] = $this->language->get('text_day');
		$data['text_week'] = $this->language->get('text_week');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		
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
		$data['total_sale'] = $this->currency->format($this->model_report_dashboard->getTotalSales(), $this->config->get('config_currency'));
		
		//$data['sales'] = $this->model_report_dashboard->getTotalSalesByMonth();
		
		// Total Orders
		$this->load->model('sale/order');
		
		$data['total_order'] = $this->model_sale_order->getTotalOrders();
		
		//$data['orders'] = $this->model_report_dashboard->getTotalOrdersByMonth();
		
		// Customers
		$this->load->model('sale/customer');
		
		$data['total_customer'] = $this->model_sale_customer->getTotalCustomers();
		
		//$data['customers'] = $this->model_report_dashboard->getTotalCustomersByMonth();

		// Marketing
		$this->load->model('marketing/marketing');
		
		$data['total_marketing'] = $this->model_marketing_marketing->getTotalMarketings();

		/*
		// Number of people onlne
		$this->load->model('report/online');

		$data['total_online'] = $this->model_report_online->getTotalCustomersOnline();
		
		$data['online'] = $this->model_report_dashboard->getTotalPeopleOnline();
		
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');
		
			$this->model_localisation_currency->updateCurrencies();
		}
		*/
		
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
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
			$range = 'month';
		}
		
		switch ($range) {
			case 'day':
				$results = $this->model_report_dashboard->getTotalOrdersByDay();
				
				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}
				
				$results = $this->model_report_dashboard->getTotalCustomersByDay();
				
				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}
				
				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['hour']);
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
				
				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['day']);
				}							
				break;
			default:
			case 'month':
				$results = $this->model_report_dashboard->getTotalOrdersByMonth();
				
				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}
				
				$results = $this->model_report_dashboard->getTotalCustomersByMonth();
				
				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}	
				
				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['day']);
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

				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['month']);
				}						
				break;	
		} 
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function marketing() {
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
			$range = 'month';
		}
		
		switch ($range) {
			case 'day':
				$results = $this->model_report_dashboard->getTotalOrdersByDay();
				
				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}
				
				$results = $this->model_report_dashboard->getTotalCustomersByDay();
				
				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}
				
				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['hour']);
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
				
				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['day']);
				}							
				break;
			default:
			case 'month':
				$results = $this->model_report_dashboard->getTotalOrdersByMonth();
				
				foreach ($results as $key => $value) {
					$json['order']['data'][] = array($key, $value['total']);
				}
				
				$results = $this->model_report_dashboard->getTotalCustomersByMonth();
				
				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}	
				
				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['day']);
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

				foreach ($results as $key => $value) {
					$json['xaxis'][] = array($key, $value['month']);
				}						
				break;	
		} 
						
		$this->response->setOutput(json_encode($json));
	}
}
?>