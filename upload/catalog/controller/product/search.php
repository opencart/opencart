<?php 
class ControllerProductSearch extends Controller { 	
	public function index() { 
    	$this->language->load('product/search');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

		$url = '';
		
		if (isset($this->request->get['keyword'])) {
			$url .= '&keyword=' . $this->request->get['keyword'];
		}
				
		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

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
       		'href'      => $this->url->http('product/search' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_separator')
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
    	$this->data['text_critea'] = $this->language->get('text_critea');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_keywords'] = $this->language->get('text_keywords');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
		$this->data['entry_search'] = $this->language->get('entry_search');
    	$this->data['entry_description'] = $this->language->get('entry_description');
		  
    	$this->data['button_search'] = $this->language->get('button_search');
   
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
		
		if (isset($this->request->get['keyword'])) {
			$this->data['keyword'] = $this->request->get['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->get['description'])) {
			$this->data['description'] = $this->request->get['description'];
		} else {
			$this->data['description'] = '';
		}
		
		if (isset($this->request->get['keyword'])) {
			$this->load->model('catalog/product');
			
			$product_total = $this->model_catalog_product->getTotalProductsByKeyword($this->request->get['keyword'], isset($this->request->get['description']) ? $this->request->get['description'] : '');
						
			if ($product_total) {
				$url = '';
	
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}    
				
				$this->load->model('catalog/review');
				$this->load->model('tool/seo_url'); 
				$this->load->helper('image');
				
        		$this->data['products'] = array();
				
				$results = $this->model_catalog_product->getProductsByKeyword($this->request->get['keyword'], isset($this->request->get['description']) ? $this->request->get['description'] : '', $sort, $order, ($page - 1) * 12, 12);
        		
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}						
					
					$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
			
					if ($special) {
						$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = FALSE;
					}
					
					$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
					
					$this->data['products'][] = array(
            			'name'    => $result['name'],
						'model'   => $result['model'],
						'rating'  => $rating,
						'stars'   => sprintf($this->language->get('text_stars'), $rating),
            			'thumb'   => image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
            			'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $special,
						'href'    => $this->model_tool_seo_url->rewrite($this->url->http('product/product&keyword=' . $this->request->get['keyword'] . $url . '&product_id=' . $result['product_id'])),
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
				
				if (isset($this->request->get['keyword'])) {
					$url .= '&keyword=' . $this->request->get['keyword'];
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}	
				
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name',
					'href'  => $this->url->http('product/search' . $url . '&sort=pd.name')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->http('product/search' . $url . '&sort=pd.name&order=DESC')
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->url->http('product/search' . $url . '&sort=p.price&order=ASC')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->url->http('product/search' . $url . '&sort=p.price&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->http('product/search' . $url . '&sort=rating&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->http('product/search' . $url . '&sort=rating&order=ASC')
				); 
				
				$url = '';

				if (isset($this->request->get['keyword'])) {
					$url .= '&keyword=' . $this->request->get['keyword'];
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}
				
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
				$pagination->url = $this->url->http('product/search' . $url . '&page=%s');
				
				$this->data['pagination'] = $pagination->render();
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
			}
		}
  
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'product/search.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();
  	}
}
?>