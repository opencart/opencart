<?php  
class ControllerModulePopular extends Controller {
	protected function index() {
		$this->load->language('module/popular');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		
		$this->load->model('catalog/review');
		
		$this->load->helper('image');
		
		$this->data['products'] = array();
		
		$results = $this->model_catalog_product->getPopularProducts(5);
			
		foreach ($results as $result) {
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
			
			$this->data['products'][] = array(
				'name'   => $result['name'],
				'price'  => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'image'  => HelperImage::resize($result['filename'], 38, 38),
				'href'   => $this->url->http('product/product&product_id=' . $result['product_id'])
			);
		}
		
		$this->id       = 'popular';
		$this->template = 'module/popular.tpl';
		
		$this->render();
	}
}
?>