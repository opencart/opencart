<?php  
class ControllerModuleBestSeller extends Controller {
	protected function index() {
		$this->language->load('module/bestseller');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->helper('image');
		
		$this->data['products'] = array();
		
		$results = $this->model_catalog_product->getBestSellerProducts($this->config->get('bestseller_limit'));
			
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	

			$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
			
			if ($special) {
				$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = FALSE;
			}
			
			$this->data['products'][] = array(											  
				'name'    => $result['name'],
				'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'special' => $special,
				'image'   => HelperImage::resize($image, 38, 38),
				'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
			);
		}
		
		$this->id       = 'bestseller';
		$this->template = $this->config->get('config_template') . 'module/bestseller.tpl';
		
		$this->render();
		
	}
}
?>