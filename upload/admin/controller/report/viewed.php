<?php
class ControllerReportViewed extends Controller {
	public function index() {     
		$this->load->language('report/viewed');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
						
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/viewed', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('catalog/product');
		
		$product_total = $this->model_catalog_product->getTotalProducts(); 
		
		$this->load->model('report/viewed');
		
		$this->data['products'] = $this->model_report_viewed->getProductViewedReport(($page - 1) * $this->config->get('config_admin_limit'), $this->config->get('config_admin_limit'));
		 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_viewed'] = $this->language->get('column_viewed');
		$this->data['column_percent'] = $this->language->get('column_percent');
		
		$this->data['button_reset'] = $this->language->get('button_reset');
		
		$this->data['reset'] = $this->url->link('report/viewed/reset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/viewed', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		 
		$this->layout = 'common/layout';
		$this->template = 'report/viewed.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function reset() {
		$this->load->language('report/viewed');
		
		$this->load->model('report/viewed');
		
		$this->model_report_viewed->reset();
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->redirect($this->url->link('report/viewed', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
}
?>