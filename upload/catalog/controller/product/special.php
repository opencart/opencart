<?php 
class ControllerProductSpecial extends Controller { 	
	public function index() { 
    	$this->language->load('product/special');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('common/home'),
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
       		'href'      => $this->url->http('product/special' . $url),
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
			$sort = 'pd.name';
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
			
			$this->load->helper('image');
				
       		$this->data['products'] = array();
				
			$results = $this->model_catalog_product->getProductSpecials($sort, $order, ($page - 1) * 12, 12);
        		
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}						
					
				$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
									
				$this->data['products'][] = array(
           			'name'    => $result['name'],
					'model'   => $result['model'],
					'rating'  => $rating,
					'stars'   => sprintf($this->language->get('text_stars'), $rating),
           			'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
           			'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special' => $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product' . $url . '&product_id=' . $result['product_id']))
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
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name',
				'href'  => $this->url->http('product/special' . $url . '&sort=pd.name')
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->http('product/special' . $url . '&sort=pd.name&order=DESC')
			);  

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'special-ASC',
				'href'  => $this->url->http('product/special' . $url . '&sort=special&order=ASC')
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'special-DESC',
				'href'  => $this->url->http('product/special' . $url . '&sort=special&order=DESC')
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->http('product/special' . $url . '&sort=rating&order=DESC')
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->http('product/special' . $url . '&sort=rating&order=ASC')
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
			$pagination->limit = 12; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->http('product/special' . $url . '&page=%s');
				
			$this->data['pagination'] = $pagination->render();
				
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/special.tpl';
			} else {
				$this->template = 'default/template/product/special.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
		
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
		} else {
      		$this->data['text_error'] = $this->language->get('text_empty');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');
	  				
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);	
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
  	}
}
?>