<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->load->language('common/home');
		
		$this->document->title = sprintf($this->language->get('title'), $this->config->get('config_store'));

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_store'));
		$this->data['welcome'] = html_entity_decode($this->config->get('config_welcome_' . $this->language->getId()));
		
		$this->data['text_latest'] = $this->language->get('text_latest');
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->helper('image');
				
		$this->data['products'] = array();

		foreach ($this->model_catalog_product->getLatestProducts(8) as $result) {			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
			 
          	$this->data['products'][] = array(
            	'name'   => $result['name'],
				'model'  => $result['model'],
            	'rating' => $rating,
				'stars'  => sprintf($this->language->get('text_stars'), $rating),
				'thumb'  => HelperImage::resize($result['filename'], 120, 120),
            	'price'  => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'href'   => $this->url->http('product/product&product_id=' . $result['product_id'])
          	);
		}
		
		$this->id       = 'content';
		$this->template = 'common/home.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();
	}
}
?>