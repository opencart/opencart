<?php
class ControllerReportPurchased extends Controller { 
	public function index() {   
		$this->load->language('report/purchased');

		$this->document->title = $this->language->get('heading_title');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/purchased&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/purchased');
		
		$product_total = $this->model_report_purchased->getTotalOrderedProducts();
		
		$this->data['products'] = array();

		$results = $this->model_report_purchased->getProductPurchasedReport(($page - 1) * $this->config->get('config_admin_limit'), $this->config->get('config_admin_limit'));
		
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'name'     => $result['name'],
				'model'    => $result['model'],
				'quantity' => $result['quantity'],
				'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=report/purchased&token=' . $this->session->data['token'] . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'report/purchased.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}	
}
?>