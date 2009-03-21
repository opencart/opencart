<?php 
class ControllerProductCategory extends Controller {  
	public function index() { 
		$this->load->language('product/category');
	
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->url->http('common/home'),
       		'text'      => $this->language->get('text_home'),
       		'separator' => FALSE
   		);	
		
		$this->load->model('catalog/category');
		
		$path = '';
		
		$parts = explode('_', $this->request->get['path']);
		
		foreach ($parts as $path_id) {
			$category_info = $this->model_catalog_category->getCategory($path_id);
				
			if (!$path) {
				$path = $path_id;
			} else {
				$path .= '_' . $path_id;
			}
				
	       	$this->document->breadcrumbs[] = array(
   	    		'href'      => $this->url->http('product/category&path=' . $path),
    	   		'text'      => $category_info['name'],
        		'separator' => $this->language->get('text_separator')
        	);
		}		
		
		$category_id = array_pop($parts);
		
		$category_info = $this->model_catalog_category->getCategory($category_id);
	
		if ($category_info) {
	  		$this->document->title = $category_info['name'];
			
			$this->document->description = $category_info['meta_description'];
			
			$this->data['heading_title'] = $category_info['name'];
			
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

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->load->model('catalog/product');  
			
			$this->load->helper('image'); 
			 
			$category_total = $this->model_catalog_category->getTotalCategoriesByCategoryId($category_id);
			$product_total = $this->model_catalog_product->getTotalProductsByCategoryId($category_id);
			
			if (($category_total) || ($product_total)) {
        		$this->data['categories'] = array();
        		
				$results = $this->model_catalog_category->getCategories($category_id);
				
        		foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}				
					
					$this->data['categories'][] = array(
            			'name'  => $result['name'],
            			'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
            			'thumb' => HelperImage::resize($image, 120, 120)
          			);
        		}
				
				$this->load->model('catalog/review');
				 
				$this->data['products'] = array();
        		
				$results = $this->model_catalog_product->getProductsByCategoryId($category_id, $sort, $order, ($page - 1) * 12, 12);
				
        		foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}
			
					$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
			 
          			$this->data['products'][] = array(
            			'name'   => $result['name'],
						'model'  => $result['model'],
            			'rating' => $rating,
						'stars'  => sprintf($this->language->get('text_stars'), $rating),
						'thumb'  => HelperImage::resize($image, 120, 120),
            			'price'  => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'href'   => $this->url->http('product/product&path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
          			);
        		}

				$url = '';
		
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}			
		
				$this->data['sorts'] = array();
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC')
				);  
 
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC')
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=p.price&order=ASC')
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=p.price&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=rating&order=DESC')
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->http('product/category&path=' . $this->request->get['path'] . '&sort=rating&order=ASC')
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
				$pagination->url = $this->url->http('product/category&path=' . $this->request->get['path'] . $url . '&page=%s');
			
				$this->data['pagination'] = $pagination->render();
			
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
			
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'product/category.tpl';
				$this->layout   = 'module/layout';
		
				$this->render();										
      		} else {
        		$this->document->title = $category_info['name'];
				
				$this->document->description = $category_info['meta_description'];
				
        		$this->data['heading_title'] = $category_info['name'];

        		$this->data['text_error'] = $this->language->get('text_empty');

        		$this->data['button_continue'] = $this->language->get('button_continue');

        		$this->data['continue'] = $this->url->http('common/home');
		
				$this->id       = 'content';
				$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
				$this->layout   = 'module/layout';
		
				$this->render();					
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
   	    		'href'      => $this->url->http('product/category&path=' . $this->request->get['path'] . $url),
    	   		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
        	);
					
			$this->document->title = $this->language->get('text_error');

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');
	  			
			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'module/layout';
		
			$this->render();
		}
  	}
}
?>