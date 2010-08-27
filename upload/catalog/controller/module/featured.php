<?php
class ControllerModulefeatured extends Controller {
	protected function index() {
		$this->language->load('module/featured');

      	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->model('tool/image');

		$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');

		$this->data['products'] = array();

		$results = $this->model_catalog_product->getfeaturedProducts($this->config->get('featured_limit'));

		foreach ($results as $result) {
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
				$add = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&amp;product_id=' . $result['product_id']);
			} else {
				$add = HTTPS_SERVER . 'index.php?route=checkout/cart&amp;product_id=' . $result['product_id'];
			}

			$this->data['products'][] = array(
				'product_id'    => $result['product_id'],
				'name'    		=> $result['name'],
				'model'   		=> $result['model'],
				'rating'  		=> $rating,
				'stars'   		=> sprintf($this->language->get('text_stars'), $rating),
				'price'   		=> $price,
				'options'   	=> $options,
				'special' 		=> $special,
				'image'   		=> $this->model_tool_image->resize($image, 38, 38),
				'thumb'   		=> $this->model_tool_image->resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
				'href'    		=> $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id']),
				'add'    		=> $add
			);
		}

		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}

		$this->id = 'featured';

		if ($this->config->get('featured_position') == 'home') {
			$this->data['heading_title'] .= (' ' . $this->language->get('text_products'));
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured_home.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/featured_home.tpl';
			} else {
				$this->template = 'default/template/module/featured_home.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/featured.tpl';
			} else {
				$this->template = 'default/template/module/featured.tpl';
			}
		}

		$this->render();
	}
}
?>