<?php 
class ControllerProductSpecial extends Controller { 	
	public function index() { 
    	$this->language->load('product/special');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTP_SERVER . 'index.php?route=common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

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
       		'href'      => HTTP_SERVER . 'index.php?route=product/special' . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_separator')
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
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
	
		$this->load->model('catalog/product');
			
		$product_total = $this->model_catalog_product->getTotalProductSpecials();
						
		if ($product_total) {
			$url = '';
				
			$this->load->model('catalog/review');
			$this->load->model('tool/seo_url');
			$this->load->model('tool/image');
			
			$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
			
       		$this->data['products'] = array();
				
			$results = $this->model_catalog_product->getProductSpecials($sort, $order, ($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
        		
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
           			'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
           			'options' => $options,
					'special' => $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'    => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product' . $url . '&product_id=' . $result['product_id']),
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
				'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=p.sort_order&order=ASC'
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=pd.name&order=ASC'
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=pd.name&order=DESC'
			);  

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'special-ASC',
				'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=special&order=ASC'
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'special-DESC',
				'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=special&order=DESC'
			); 
			
			if ($this->config->get('config_review')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=rating&order=DESC'
				);
					
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=rating&order=ASC'
				);
			}
			
			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_model_asc'),
					'value' => 'p.model-ASC',
					'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=p.model&order=ASC'
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => HTTP_SERVER . 'index.php?route=product/special' . $url . '&sort=p.model&order=DESC'
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
			$pagination->url = HTTP_SERVER . 'index.php?route=product/special' . $url . '&page={page}';
				
			$this->data['pagination'] = $pagination->render();
				
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/special.tpl';
			} else {
				$this->template = 'default/template/product/special.tpl';
			}
			
			$this->children = array(
				'common/column_right',
				'common/column_left',
				'common/footer',
				'common/header'
			);
		
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
		} else {
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
  	}
}
?>