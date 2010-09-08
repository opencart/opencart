<?php 
class ControllerAccountHistory extends Controller {	
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=account/history';

	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	}
 
    	$this->language->load('account/history');

    	$this->document->title = $this->language->get('heading_title');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/account',
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/history',
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
			
			$this->data['action'] = HTTP_SERVER . 'index.php?route=account/history';
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
      		$this->data['orders'] = array();
			
			$results = $this->model_account_order->getOrders(($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
      		
			foreach ($results as $result) {
        		$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);

        		$this->data['orders'][] = array(
          			'order_id'   => $result['order_id'],
          			'name'       => $result['firstname'] . ' ' . $result['lastname'],
          			'status'     => $result['status'],
          			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'products'   => $product_total,
          			'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
					'href'       => HTTPS_SERVER . 'index.php?route=account/invoice&order_id=' . $result['order_id']
        		);
      		}

			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = HTTP_SERVER . 'index.php?route=account/history&page={page}';
			
			$this->data['pagination'] = $pagination->render();

      		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/account';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/history.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/history.tpl';
			} else {
				$this->template = 'default/template/account/history.tpl';
			}
			
			$this->children = array(
				'common/column_right',
				'common/footer',
				'common/column_left',
				'common/header'
			);	
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));				
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/account';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_right',
				'common/footer',
				'common/column_left',
				'common/header'
			);
					
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));				
		}
	}
}
?>
