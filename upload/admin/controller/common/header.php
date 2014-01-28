<?php 
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle(); 
			
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();	
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
		
		$this->load->language('common/header');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_order'] = $this->language->get('text_order');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_complete_status'] = $this->language->get('text_complete_status');	
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_front'] = $this->language->get('text_front');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_homepage'] = $this->language->get('text_homepage');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support'); 
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		$data['text_profile'] = $this->language->get('text_profile');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_logout'] = $this->language->get('text_logout');
		
		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = '';
			
			$data['home'] = $this->url->link('common/dashboard', '', 'SSL');
		} else {
			$data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
			$data['profile'] = $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $this->user->getId(), 'SSL');
			$data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');
				
			// Orders
			$this->load->model('sale/order');
			
			$data['order_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status_id' => $this->config->get('config_order_status_id')));
			$data['order_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . 'filter_order_status_id='.  $this->config->get('config_order_status_id'), 'SSL');
			
			$data['complete_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status_id' => $this->config->get('config_complete_status_id')));
			$data['complete_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . 'filter_order_status_id='.  $this->config->get('config_complete_status_id'), 'SSL');
			
			// Customers
			$this->load->model('report/dashboard');
			
			$data['online_total'] = $this->model_report_dashboard->getTotalCustomersOnline();			
			
			$data['customer_approval_total'] = $this->model_sale_customer->getTotalCustomers(array('filter_approved' => false));
			$data['customer_approval'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . 'filter_order_status_id='.  $this->config->get('config_complete_status_id'), 'SSL');

			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);			
			
			$this->load->model('setting/store');
			
			$results = $this->model_setting_store->getStores();
			
			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}
		
		$this->load->model('user/user');

		$this->load->model('tool/image');

		$user_info = $this->model_user_user->getUser($this->user->getId());

		if ($user_info) {
			$data['username'] = $user_info['firstname'] . ' ' . $user_info['lastname'];

			if (is_file(DIR_IMAGE . $user_info['image'])) {
				$data['image'] = $this->model_tool_image->resize($user_info['image'], 24, 24);
			} else {
				$data['image'] = '';
			}
		} else {
			$data['username'] = '';
			$data['image'] = '';
		}
					
		return $this->load->view('common/header.tpl', $data);
	}
}