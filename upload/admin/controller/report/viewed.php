<?php
class ControllerReportViewed extends Controller {
	public function index() {     
		$this->load->language('report/viewed');

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
       		'href'      => HTTPS_SERVER . 'index.php?route=report/viewed&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
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
		
		$this->data['reset'] = HTTPS_SERVER . 'index.php?route=report/viewed/reset&token=' . $this->session->data['token'] . $url;

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
		$pagination->url = HTTPS_SERVER . 'index.php?route=report/viewed&token=' . $this->session->data['token'] . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		 
		$this->template = 'report/viewed.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
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
		
		$this->redirect(HTTPS_SERVER . 'index.php?route=report/viewed&token=' . $this->session->data['token'] . $url);
	}
}
?>