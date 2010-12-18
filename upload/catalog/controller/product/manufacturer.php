<?php 
class ControllerProductManufacturer extends Controller {  
	public function index() { 
		$this->language->load('product/manufacturer');

		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('tool/seo_url'); 
		$this->load->model('tool/image');
		
		$this->document->breadcrumbs = array();
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_id = $this->request->get['manufacturer_id'];
		} else {
			$manufacturer_id = 0;
		}
		
		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
	
		if ($manufacturer_info) {
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id']),
        		'text'      => $manufacturer_info['name'],
        		'separator' => $this->language->get('text_separator')
      		);
					  		
			$this->document->title = $manufacturer_info['name'];
									
			$this->data['heading_title'] = $manufacturer_info['name'];

			$this->data['text_sort'] = $this->language->get('text_sort');
		
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($this->request->get['manufacturer_id']);
			
			if ($product_total) {
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}				

				if (isset($this->request->get['sort'])) {
					$sort = $this->request->get['sort'];
				} else {
					$sort = 'p.sort_order';
				}

				if (isset($this->request->get['order'])) {
					$order = $this->request->get['order'];
				} else {
					$order = 'ASC';
				}
			
				$this->load->model('catalog/review');
				
				$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
				
				$this->data['products'] = array();
        		
				$results = $this->model_catalog_product->getProductsByManufacturerId($this->request->get['manufacturer_id'], $sort, $order, ($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
				
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
						'href'    => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&product_id=' . $result['product_id']),
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

				$url = '';
		
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}	
				
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'p.sort_order-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.sort_order&order=ASC')
				);
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order=ASC')
				);  
 
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order=DESC')
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=ASC')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=DESC')
				); 
				
				if ($this->config->get('config_review')) {
					$this->data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_desc'),
						'value' => 'rating-DESC',
						'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=DESC')
					);
					
					$this->data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_asc'),
						'value' => 'rating-ASC',
						'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=ASC')
					);
				}
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_model_asc'),
					'value' => 'p.model-ASC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.model&order=ASC')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_model_desc'),
					'value' => 'p.model-DESC',
					'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.model&order=DESC')
				);

				$url = '';
		
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $this->config->get('config_catalog_limit');
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page={page}');
			
				$this->data['pagination'] = $pagination->render();

				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/product/manufacturer.tpl';
				} else {
					$this->template = 'default/template/product/manufacturer.tpl';
				}	
				
				$this->children = array(
					'common/column_right',
					'common/column_left',
					'common/footer',
					'common/header'
				);		
				
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));										
      		} else {
        		$this->document->title = $manufacturer_info['name'];

        		$this->data['heading_title'] = $manufacturer_info['name'];

        		$this->data['text_error'] = $this->language->get('text_empty');

        		$this->data['button_continue'] = $this->language->get('button_continue');

        		$this->data['continue'] = HTTP_SERVER . 'index.php?route=common/home';
		
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
					'common/column_right',
					'common/column_left',
					'common/footer',
					'common/header'
				);		
				
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));					
      		}
    	} else {
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}	
			
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/manufacturer&manufacturer_id=' . $manufacturer_id . $url),
        		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
      		);
					
			$this->document->title = $this->language->get('text_error');

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = HTTP_SERVER . 'index.php?route=common/home';
	  			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_right',
				'common/column_left',
				'common/footer',
				'common/header'
			);		
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
  	}
}
?>