<?php  
class ControllerReportCustomerOnline extends Controller {  
	public function index() {
		$this->language->load('report/customer_online');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('report/customer_online', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->load->model('report/online');
		$this->load->model('sale/customer');

		$this->data['customers'] = array();

		$data = array(
			'filter_ip'       => $filter_ip, 
			'filter_customer' => $filter_customer, 
			'start'           => ($page - 1) * 20,
			'limit'           => 20
		);

		$customer_total = $this->model_report_online->getTotalCustomersOnline($data);

		$results = $this->model_report_online->getCustomersOnline($data);

		foreach ($results as $result) {
			$action = array();

			if ($result['customer_id']) {
				$action[] = array(
					'text' => 'Edit',
					'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL')
				);
			}

			$customer_info = $this->model_sale_customer->getCustomer($result['customer_id']);

			if ($customer_info) {
				$customer = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
			} else {
				$customer = $this->language->get('text_guest');
			}

			$this->data['customers'][] = array(
				'ip'         => $result['ip'],
				'customer'   => $customer,
				'url'        => $result['url'],
				'referer'    => $result['referer'],
				'date_added' => date('d/m/Y H:i:s', strtotime($result['date_added'])),
				'action'     => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_referer'] = $this->language->get('column_referer');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = 20; 
		$pagination->url = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_ip'] = $filter_ip;		

		$this->template = 'report/customer_online.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);

		$this->response->setOutput($this->render());
	}
}
?>