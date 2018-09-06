<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleFeaturedProduct extends Controller {
	public function index($setting) {
		
		
		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
		
		$results = array();
		
		$this->load->model('catalog/cms');
		
			if (isset($this->request->get['manufacturer_id'])) {
					
					$filter_data = array(
						'manufacturer_id'  => $this->request->get['manufacturer_id'],
						'limit' => $setting['limit']
					);
					
					$results = $this->model_catalog_cms->getProductRelatedByManufacturer($filter_data);
				
			} else {
				
					$parts = explode('_', (string)$this->request->get['path']);
					
					if(!empty($parts) && is_array($parts)) {
					
						$filter_data = array(
							'category_id'  => array_pop($parts),
							'limit' => $setting['limit']
						);
						
					$results = $this->model_catalog_cms->getProductRelatedByCategory($filter_data);
								
					}
			}
		
		$this->load->language('extension/module/featured_product');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();
		
		if (!empty($results)) {
			
			foreach ($results as $product) {

				if ($product) {
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product['special']) {
						$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product['rating'];
					} else {
						$rating = false;
					}
					

					$data['products'][] = array(
						'product_id'  => $product['product_id'],
						'thumb'       => $image,
						'name'        => $product['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id'])
					);
				}
			}
		}
		
		return $this->load->view('extension/module/featured_product', $data);

	}
	
}