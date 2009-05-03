<?php   
class ControllerCommonHome extends Controller {   
	public function index() {
    	$this->load->language('common/home');
	 
		$this->document->title = $this->language->get('heading_title');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_week'] = $this->language->get('text_week');
		$this->data['text_month'] = $this->language->get('text_month');
		$this->data['text_year'] = $this->language->get('text_year');

		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_firstname'] = $this->language->get('column_firstname');
		$this->data['column_lastname'] = $this->language->get('column_lastname');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_range'] = $this->language->get('entry_range');
		
		$this->data['tab_stats'] = $this->language->get('tab_stats');
    	$this->data['tab_order'] = $this->language->get('tab_order');
		$this->data['tab_customer'] = $this->language->get('tab_customer');
  		
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
		
		$this->load->model('customer/order');
		
		$this->data['orders'] = array();
		
		$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$results = $this->model_customer_order->getOrders($data);
    	
    	foreach ($results as $result) {
			$action = array();
			 
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('customer/order/update&order_id=' . $result['order_id'])
			);
					
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'action'     => $action
			);
		}
		
		$this->load->model('customer/customer');
		
		$this->data['customers'] = array();

		$data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$results = $this->model_customer_customer->getCustomers($data);

    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('customer/customer/update&customer_id=' . $result['customer_id'])
			);
					
			$this->data['customers'][] = array(
				'lastname'   => $result['lastname'],
				'firstname'  => $result['firstname'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'     => $action
			);
		}

		$this->load->model('localisation/currency');
		
		$this->model_localisation_currency->updateCurrencies();
		
		$this->id       = 'content'; 
		$this->template = 'common/home.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();  
  	}
	
	public function report() {
		$this->load->language('common/home');
		
		$url  = 'http://chart.apis.google.com/chart?cht=lc';
		$url .= '&chdl=' . $this->language->get('text_order') . '|' . $this->language->get('text_customer');
		$url .= '&chco=FF0000,00FF00';
		$url .= '&chs=718x330';
		$url .= '&chxt=x,y';
		$url .= '&chg=20,10';
				
		switch (@$this->request->get['range']) {
			default:
			case 'day':
				$orders = array();
				$customers = array();
				$labels = array();
				
				for ($i = 0; $i <= 23; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "' GROUP BY HOUR(date_added) ORDER BY date_added ASC");

					if ($query->num_rows) {
						$orders[] = $query->row['total'];
					} else {
						$orders[] = 0;
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "' GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					
					if ($query->num_rows) {
						$customers[] = $query->row['total'];
					} else {
						$customers[] = 0;
					}
					
					$labels[] = $i;
				}
				
				$url .= '&chxl=0:|' . implode('|', $labels) . '|';
				$url .= '&chd=t:';
				
				if ($orders) {
					$url .= implode(',', $orders);
				}
				
				if (($orders) && ($customers)) {
					$url .= '|';
				}
				
				if ($customers) {
					$url .= implode(',', $customers);
				}
				break;
			case 'week':
				$orders = array();
				$customers = array();
				$labels = array();
				
				$week = mktime(0, 0, 0, date('m'), date('d') - date('w'), date('Y'));

				for ($i = 1; $i <= 7; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND DATE(date_added) = DATE('" . date('Y-m-d', $week + ($i * 86400)) . "') GROUP BY DATE(date_added)");

					if ($query->num_rows) {
						$orders[] = $query->row['total'];
					} else { 
						$orders[] = 0;
					}
				
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = DATE('" . date('Y-m-d', $week + ($i * 86400)) . "') GROUP BY DATE(date_added)");
				
					if ($query->num_rows) {
						$customers[] = $query->row['total'];
					} else {
						$customers[] = 0;
					}
					
					$labels[] = date('D', $week + ($i * 86400));
				}
				
				$url .= '&chxl=0:|' . implode('|', $labels) . '|';
				$url .= '&chd=t:';
				
				if ($orders) {
					$url .= implode(',', $orders);
				}
				
				if (($orders) && ($customers)) {
					$url .= '|';
				}
				
				if ($customers) {
					$url .= implode(',', $customers);
				}		
				break;
			case 'month':
				$orders = array();
				$customers = array();
				$labels = array();
				
				$last_day_of_the_month = mktime(23, 59, 59, date('m'), 0, date('Y')); 

				for ($i = 1; $i <= date('j', $last_day_of_the_month); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND DATE(date_added) = DATE('" . date('Y-m') . '-' . (int)$i . "') GROUP BY DATE(date_added)");

					if ($query->num_rows) {
						$orders[] = $query->row['total'];
					} else { 
						$orders[] = 0;
					}
				
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = DATE('" . date('Y-m') . '-' . (int)$i . "') GROUP BY DATE(date_added)");
				
					if ($query->num_rows) {
						$customers[] = $query->row['total'];
					} else {
						$customers[] = 0;
					}
					
					$labels[] = $i;
				}
				
				$url .= '&chxl=0:|' . implode('|', $labels) . '|';
				$url .= '&chd=t:';
				
				if ($orders) {
					$url .= implode(',', $orders);
				}
				
				if (($orders) && ($customers)) {
					$url .= '|';
				}
				
				if ($customers) {
					$url .= implode(',', $customers);
				}				
				break;
			case 'year':
				$orders = array();
				$customers = array();
				$labels = array();

				for ($i = 1; $i <= 12; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND YEAR(date_added) = YEAR(NOW()) AND MONTH(date_added) = '" . (int)$i . "' GROUP BY MONTH(date_added)");

					if ($query->num_rows) {
						$orders[] = $query->row['total'];
					} else { 
						$orders[] = 0;
					}
				
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE YEAR(date_added) = YEAR(NOW()) AND MONTH(date_added) = '" . (int)$i . "' GROUP BY DATE(date_added)");
				
					if ($query->num_rows) {
						$customers[] = $query->row['total'];
					} else {
						$customers[] = 0;
					}
					
					$labels[] = date('M', mktime(0, 0, 0, $i, 1, date('Y')));
				}
				
				$url .= '&chxl=0:|' . implode('|', $labels) . '|';
				$url .= '&chd=t:';
				
				if ($orders) {
					$url .= implode(',', $orders);
				}
				
				if (($orders) && ($customers)) {
					$url .= '|';
				}
				
				if ($customers) {
					$url .= implode(',', $customers);
				}
				break;	
		}
		
		$this->response->setOutput($url);
	}
}
?>