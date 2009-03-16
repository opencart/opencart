<?php 
class ControllerAccountHistory extends Controller {	
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->https('account/history');

	  		$this->redirect($this->url->https('account/login'));
    	}
 
    	$this->load->language('account/history');

    	$this->document->title = $this->language->get('heading_title');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/history'),
        	'text'      => $this->language->get('text_history'),
        	'separator' => $this->language->get('text_separator')
      	);
				
		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalOrders();
		
		if ($order_total) {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_order'] = $this->language->get('text_order');
      		$this->data['text_status'] = $this->language->get('text_status');
     		$this->data['text_date_added'] = $this->language->get('text_date_added');
      		$this->data['text_customer'] = $this->language->get('text_customer');
      		$this->data['text_products'] = $this->language->get('text_products');
      		$this->data['text_total'] = $this->language->get('text_total');

      		$this->data['button_view'] = $this->language->get('button_view');
      		$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['action'] = $this->url->http('account/history');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
      		$this->data['orders'] = array();
			
			$results = $this->model_account_order->getOrders(($page - 1) * 20, 20);
      		
			foreach ($results as $result) {
        		$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

        		$this->data['orders'][] = array(
          			'order_id'   => $result['order_id'],
          			'name'       => $result['firstname'] . ' ' . $result['lastname'],
          			'status'     => $result['status'],
          			'date_added' => date('d F Y', strtotime($result['date_added'])),
          			'products'   => $product_total,
          			'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
					'href'       => $this->url->https('account/invoice&order_id=' . $result['order_id'])
        		);
      		}

			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = 20; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->http('account/history&page=%s');
			
			$this->data['pagination'] = $pagination->render();

      		$this->data['continue'] = $this->url->https('account/account');
			
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'account/history.tpl';
			$this->layout   = 'module/layout';
		
			$this->render();				
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->https('account/account');
			
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'module/layout';
		
			$this->render();				
		}
	}
}
?>
