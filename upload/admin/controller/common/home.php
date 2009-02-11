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
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
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
					
		$this->id       = 'content'; 
		$this->template = 'common/home.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();  
  	}
	
	public function report() {
    	$this->load->language('common/home');
		
		$data = array();
		
		$data['order'] = array();
		$data['customer'] = array();
		$data['xaxis'] = array();
		
		$data['order']['label'] = $this->language->get('text_order');
		$data['customer']['label'] = $this->language->get('text_customer');
		
		switch (@$this->request->get['range']) {
			case 'day':
				for ($i = 0; $i <= date('G'); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE confirm = '1' AND (DATE(date_added) = '" . date('Y-m-d') . "' AND HOUR(date_added) = '" . (int)$i . "') GROUP BY HOUR(date_added)");
			
					$data['order']['data'][]  = array(date('G', strtotime('-' . (int)$i . ' hour')), (int)@$query->row['total']);
			
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = '" . date('Y-m-d') . "' AND HOUR(date_added) = '" . (int)$i . "' GROUP BY HOUR(date_added)");
			
					$data['customer']['data'][] = array(date('G', strtotime('-' . (int)$i . ' hour')), (int)@$query->row['total']);
			
					$data['xaxis'][] = array(date('G', strtotime('-' . (int)$i . ' hour')), date('H', strtotime('-' . (int)$i . ' hour')));
				}					
				break;
			case 'week':
				for ($i = 0; $i < 7; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE confirm = '1' AND DATE(date_added) = '" . date('Y-m-d', strtotime('-' . (int)$i . ' day')) . "' GROUP BY DAY(date_added)");
			
					$data['order']['data'][]  = array(date('d', strtotime('-' . (int)$i . ' day')), (int)@$query->row['total']);
			
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = '" . date('Y-m-d', strtotime('-' . (int)$i . ' day')) . "' GROUP BY DAY(date_added)");
			
					$data['customer']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), (int)@$query->row['total']);
			
					$data['xaxis'][] = array(date('d', strtotime('-' . (int)$i . ' day')), date('d/m', strtotime('-' . (int)$i . ' day')));
				}			
				break;
			default:
			case 'month':
				for ($i = 0; $i < date('j'); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE confirm = '1' AND (DATE(date_added) = '" . date('Y-m-d', strtotime('-' . (int)$i . ' day')) . "') GROUP BY DAY(date_added)");
			
					$data['order']['data'][]  = array(date('d', strtotime('-' . (int)$i . ' day')), (int)@$query->row['total']);
			
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = '" . date('Y-m-d', strtotime('-' . (int)$i . ' day')) . "' GROUP BY DAY(date_added)");
			
					$data['customer']['data'][]  = array(date('d', strtotime('-' . (int)$i . ' day')), (int)@$query->row['total']);
			
					$data['xaxis'][] = array(date('d', strtotime('-' . (int)$i . ' day')), date('d/m', strtotime('-' . (int)$i . ' day')));
				}
				break;
			case 'year':
				for ($i = 0; $i < date('n'); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE confirm = '1' AND (YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . date('m', strtotime('-' . $i . ' month')) . "') GROUP BY MONTH(date_added)");
			
					$data['order']['data'][]  = array(date('n', strtotime('-' . (int)$i . ' month')), (int)@$query->row['total']);
			
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . date('m', strtotime('-' . $i . ' month')) . "' GROUP BY MONTH(date_added)");
			
					$data['customer']['data'][]  =array(date('n', strtotime('-' . (int)$i . ' month')), (int)@$query->row['total']);
			
					$data['xaxis'][] = array(date('n', strtotime('-' . (int)$i . ' month')), date('m', strtotime('-' . (int)$i . ' month')));
				}			
				break;	
		}
				
		$this->response->setOutput(json_encode($data));
	}
}
?>