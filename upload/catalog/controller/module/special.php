<?php  
class ControllerModuleSpecial extends Controller {
	protected function index() {
		$this->language->load('module/special');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->model('tool/image');
			
		$this->data['products'] = array();
		
		$results = $this->model_catalog_product->getProductSpecials($this->config->get('special_limit'));
			
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	

			$special = FALSE;
			
			$discount = $this->model_catalog_product->getProductDiscount($result['product_id']);
			
			if ($discount) {
				$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			
				$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
			
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				}						
			}
			
			$this->data['products'][] = array(											  
				'name'    => $result['name'],
				'price'   => $price,
				'special' => $special,
				'image'   => $this->model_tool_image->resize($image, 38, 38),
				'href'    => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id'])
			);
		}

		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
		
		$this->id = 'special';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
		} else {
			$this->template = 'default/template/module/special.tpl';
		}
		
		$this->render();
	}
}
?>