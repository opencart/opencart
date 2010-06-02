<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->language->load('common/home');
		
		$this->document->title = $this->config->get('config_title');
		$this->document->description = $this->config->get('config_meta_description');
		
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$this->load->model('setting/store');
		
		if (!$this->config->get('config_store_id')) {
			$this->data['welcome'] = html_entity_decode($this->config->get('config_description_' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
		} else {
			$store_info = $this->model_setting_store->getStore($this->config->get('config_store_id'));
			
			if ($store_info) {
				$this->data['welcome'] = html_entity_decode($store_info['description'], ENT_QUOTES, 'UTF-8');
			} else {
				$this->data['welcome'] = '';
			}
		}
		
		$this->data['text_latest'] = $this->language->get('text_latest');
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->model('tool/image');
		
		$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
		
		$this->data['products'] = array();

		foreach ($this->model_catalog_product->getLatestProducts(8) as $result) {			
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			if ($this->config->get('config_review')) {
				$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
			} else {
				$rating = false;
			}
			
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
			
			$options = $this->model_catalog_product->getProductOptions($result['product_id']);
					
			if ($options) {
				$add = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id']);
			} else {
				$add = HTTPS_SERVER . 'index.php?route=checkout/cart&product_id=' . $result['product_id'];
			}
			
          	$this->data['products'][] = array(
            	'name'    => $result['name'],
				'model'   => $result['model'],
            	'rating'  => $rating,
				'stars'   => sprintf($this->language->get('text_stars'), $rating),
				'thumb'   => $this->model_tool_image->resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
            	'price'   => $price,
            	'options' => $options,
				'special' => $special,
				'href'    => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id']),
				'add'	  => $add
          	);
		}

		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		
		$this->children = array(
			'common/column_right',
			'common/column_left',
			'common/footer',
			'common/header'
		);
		
		$this->load->model('checkout/extension');
		
		$module_data = $this->model_checkout_extension->getExtensionsByPosition('module', 'home');
		
		$this->data['modules'] = $module_data;
		
		foreach ($module_data as $result) {
			$this->children[] = 'module/' . $result['code'];
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>